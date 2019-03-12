import os
import json
from typing import Dict, List, NamedTuple

import pymysql.cursors

Note = int
Quantity = int
RequestedAmount = int

NoteAvailability = NamedTuple('NoteAvailability', [('note', Note), ('quantity', Quantity)])


class ATM:
    def factory(di_component=None):
        return ATM(MysqlGateway())

    def __init__(self, gateway):
        self._gateway = gateway

    def withdraw(self, amount: RequestedAmount):
        return ATM._withdraw(self._gateway.load_available_notes(), amount)

    @staticmethod
    def _withdraw(available_notes: List[NoteAvailability], amount: RequestedAmount):
        """ available_notes must be sorted from the highest value to the lowest """
        rest_amount = amount
        user_notes = []
        for note_availability in available_notes:
            if note_availability.note <= rest_amount:
                quantity_of_notes = ATM._get_maximum_of_notes_to_complete_value(rest_amount, note_availability.note)
                if quantity_of_notes > note_availability.quantity:
                    quantity_of_notes = note_availability.quantity

                user_notes.append((note_availability.note, quantity_of_notes))
                rest_amount = rest_amount - (note_availability.note * quantity_of_notes)

        return user_notes

    @staticmethod
    def _get_maximum_of_notes_to_complete_value(value_to_cover, note_value):
        number_of_notes = 1
        while value_to_cover >= (note_value * (number_of_notes + 1)):
            number_of_notes += 1

        return number_of_notes


def dict_to_note_availability(data: Dict) -> NoteAvailability:
    return NoteAvailability(note=data['note'], quantity=data['quantity'])


class Gateway:
    def load_available_notes(self) -> List[NoteAvailability]:
        raise NotImplementedError("Should have implemented this")


class FileGateway(Gateway):
    def load_available_notes(self) -> List[NoteAvailability]:
        current_path = os.path.dirname(os.path.realpath(__file__))
        handle = open(f'{current_path}/../data/atm.json')
        result = json.loads(handle.read())
        handle.close()
        return list(map(dict_to_note_availability, result['available_notes']))


class MysqlGateway(Gateway):
    def load_available_notes(self) -> List[NoteAvailability]:
        connection = pymysql.connect(
            host='127.0.0.1',
            user='gandalf',
            password='gandalf',
            db='test',
            port=3306,
            charset='utf8',
            cursorclass=pymysql.cursors.DictCursor)

        try:

            with connection.cursor() as cursor:
                sql = "SELECT `note`, `quantity` FROM `note_availability`"
                cursor.execute(sql)
                result = cursor.fetchall()
                return list(map(dict_to_note_availability, result))
        finally:
            connection.close()
