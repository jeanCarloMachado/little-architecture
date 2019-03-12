import os
import unittest
import json
from typing import Dict, List, Generic, NamedTuple

Note = int
Quantity = int
RequestedAmount = int

NoteAvailability = NamedTuple('NoteAvailability', [('note', Note), ('quantity', Quantity)])


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


class ATM:
    def factory(di_component=None):
        return ATM(FileGateway())

    def __init__(self, gateway):
        self._gateway = gateway

    def withdraw(self, amount: RequestedAmount):
        return ATM._withdraw(self._gateway.load_available_notes(), amount)

    @staticmethod
    def _withdraw(available_notes: List[NoteAvailability], amount: RequestedAmount):
        """ available_notes must be sorted from the highest value to the lowest """
        print(" gandalf", available_notes)
        if available_notes is None:
            return []
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


class ATMUnitTest(unittest.TestCase):
    def test_returns_one_when_have_one(self):
        result = ATM._withdraw([NoteAvailability(note=1, quantity=1)], 1)
        self.assertEquals(result, [(1, 1)])

    def test_returns_none_when_have_none(self):
        available_notes = None
        result = ATM._withdraw(available_notes, 1)
        self.assertEquals(result, [])

    def test_return_highest_note_that_matches_value(self):
        available_notes = [NoteAvailability(note=10, quantity=1), NoteAvailability(note=5, quantity=2)]
        result = ATM._withdraw(available_notes, 10)
        self.assertEqual(result, [(10, 1)])
        result = ATM._withdraw(available_notes, 5)
        self.assertEquals(result, [(5, 1)])

    def test_join_two_of_the_same(self):
        available_notes = [NoteAvailability(note=10, quantity=2), NoteAvailability(note=5, quantity=2)]
        result = ATM._withdraw(available_notes, 20)
        self.assertEquals(result, [(10, 2)])

    def test_use_different_notes_to_complete_value(self):
        available_notes = [NoteAvailability(note=10, quantity=2), NoteAvailability(note=5, quantity=2)]
        result = ATM._withdraw(available_notes, 15)
        self.assertEquals(result, [(10, 1), (5, 1)])

    def test_does_not_uses_notes_that_are_not_available(self):
        available_notes = [NoteAvailability(note=10, quantity=1), NoteAvailability(note=5, quantity=2)]
        result = ATM._withdraw(available_notes, 20)
        self.assertEquals(result, [(10, 1), (5, 2)])

    def test_complex_case(self):
        available_notes = [
            NoteAvailability(note=10, quantity=1),
            NoteAvailability(note=5, quantity=3),
            NoteAvailability(note=1, quantity=1)
        ]
        result = ATM._withdraw(available_notes, 16)
        self.assertEquals(result, [(10, 1), (5, 1), (1, 1)])


class ATMIntegrationTest(unittest.TestCase):
    def test_simple_case(self):
        gateway = Gateway()
        gateway.load_available_notes = lambda: [NoteAvailability(note=1, quantity=1)]
        atm = ATM(gateway)
        result = atm.withdraw(1)
        self.assertEquals(result, [(1, 1)])


class ATMFunctionalTest(unittest.TestCase):
    def test_simple_case(self):
        atm = ATM.factory()
        result = atm.withdraw(1)
        self.assertEqual(result, [(1, 1)])