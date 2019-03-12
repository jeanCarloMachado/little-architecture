import os
import unittest
import json
from typing import Dict, List, NamedTuple
from bank.atm import ATM, Gateway, FileGateway, MysqlGateway, NoteAvailability

import pymysql.cursors


class ATMUnitTest(unittest.TestCase):
    def test_returns_one_when_have_one(self):
        result = ATM._withdraw([NoteAvailability(note=1, quantity=1)], 1)
        self.assertEquals(result, [(1, 1)])

    def test_returns_empty_when_have_none(self):
        available_notes = []
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
    """ideally this test should be done at the api level"""

    def test_simple_case(self):
        atm = ATM.factory()
        result = atm.withdraw(1)
        self.assertEqual(result, [(1, 1)])
