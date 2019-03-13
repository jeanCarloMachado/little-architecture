from functools import wraps
import unittest
import json
import os
import pymysql
from typing import Dict, List, NamedTuple

Note = int
Quantity = int
RequestedAmount = int

NoteAndQuantity = NamedTuple('NoteAndQuantity', [('note', Note), ('quantity', Quantity)])


def file_logger(file_name, string_to_log):
    file = open(file_name, "a")
    file.write("appended: %s \n" % string_to_log)
    file.close()


def log_on_call(log_str, func):
    @wraps(func)
    def internal_decorator(*args, **kwargs):
        file_logger("/tmp/foo", log_str)
        return func(*args, **kwargs)

    return internal_decorator


class Gateway:
    def load_available_notes(self) -> List[NoteAndQuantity]:
        raise NotImplementedError('implement me!')


class DBGateway(Gateway):
    def load_available_notes(self) -> List[NoteAndQuantity]:
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
                return list(map(lambda data: NoteAndQuantity(data['note'], data['quantity']), result))
        finally:
            connection.close()


class FileGateway(Gateway):
    def load_available_notes(self) -> List[NoteAndQuantity]:
        current_path = os.path.dirname(os.path.realpath(__file__))
        handle = open(f'{current_path}/../data/atm_state.json')
        result = json.loads(handle.read())
        handle.close()
        return list(map(lambda data: NoteAndQuantity(data['note'], data['quantity']), result['available_notes']))


class Atm:
    def factory(di_component=None):
        gateway = FileGateway()

        func = gateway.load_available_notes
        wrapped = log_on_call(" LOADING NOTES! ", func)
        gateway.load_available_notes = wrapped

        return Atm(gateway)

    def factoryDB(di_component=None):
        return Atm(DBGateway())

    def __init__(self, gateway):
        self._gateway = gateway

    def withdraw(self, quantity_requested) -> List[NoteAndQuantity]:
        return Atm._withdraw(self._gateway.load_available_notes(), quantity_requested)

    @staticmethod
    def _withdraw(money_available: List[NoteAndQuantity], quantity_requested):
        """ data comes sorted by biggest notes """
        rest = quantity_requested
        result = []
        for entry in money_available:
            if quantity_requested >= entry.note:
                quantity_of_notes = Atm._get_quantity_of_notes_minor_than_value(entry.note, rest)
                if quantity_of_notes > entry.quantity:
                    quantity_of_notes = entry.quantity
                result.append(NoteAndQuantity(entry.note, quantity_of_notes))
                rest -= entry.note * quantity_of_notes

        return result

    @staticmethod
    def _get_quantity_of_notes_minor_than_value(note, quantity_requested):
        quantity_of_notes = 1
        while (quantity_requested >= note * (quantity_of_notes + 1)):
            quantity_of_notes += 1

        return quantity_of_notes


class TestAtm(unittest.TestCase):
    def test_no_money_returned_when_none_available(self):
        result = Atm._withdraw([], 10)
        self.assertEquals(result, [])

    def test_returns_the_note_requested_when_it_is_avaiable(self):
        result = Atm._withdraw([NoteAndQuantity(1, 1)], 1)
        self.assertEquals(result, [NoteAndQuantity(1, 1)])

        result = Atm._withdraw([NoteAndQuantity(5, 1)], 5)
        self.assertEquals(result, [NoteAndQuantity(5, 1)])

    def test_compose_result_with_minor_notes(self):
        result = Atm._withdraw([NoteAndQuantity(10, 2)], 20)
        self.assertEquals(result, [NoteAndQuantity(10, 2)])

        result = Atm._withdraw([NoteAndQuantity(10, 2), NoteAndQuantity(5, 1)], 15)
        self.assertEquals(result, [NoteAndQuantity(10, 1), NoteAndQuantity(5, 1)])

    def test_do_not_return_non_available_notes_use_smaller_ones(self):
        result = Atm._withdraw([NoteAndQuantity(10, 1), NoteAndQuantity(5, 2)], 20)
        self.assertEquals(result, [NoteAndQuantity(10, 1), NoteAndQuantity(5, 2)])


class IntegrationAtm(unittest.TestCase):
    def test_simplest(self):
        gateway = Gateway()
        gateway.load_available_notes = lambda: [NoteAndQuantity(1, 1)]
        atm = Atm(gateway)
        result = atm.withdraw(1)
        self.assertEquals(result, [NoteAndQuantity(1, 1)])


class FunctionalAtm(unittest.TestCase):
    def test_file(self):
        atm = Atm.factory()
        result = atm.withdraw(1)
        self.assertEquals(result, [NoteAndQuantity(1, 1)])

    def test_db(self):
        atm = Atm.factoryDB()
        result = atm.withdraw(5)
        self.assertEquals(result, [NoteAndQuantity(1, 1)])
