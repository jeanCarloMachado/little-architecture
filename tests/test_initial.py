import unittest
from typing import Dict, List

Note = int
Quantity = int
RequestedAmount = int


def withdraw(available_notes, amount: RequestedAmount):
    if available_notes is None:
        return []
    if amount in available_notes:
        return [(amount, 1)]

    rest_amount = amount
    user_notes = []
    for current_note, quantity in available_notes:
        if rest_amount % current_note == 0:
            quantity_of_notes = int(rest_amount / current_note)
            user_notes.append((current_note, quantity_of_notes))
            return user_notes

        if current_note < rest_amount:
            user_notes[current_note] = 1

    return user_notes


class TestInitial(unittest.TestCase):
    def test_returns_one_when_have_one(self):
        result = withdraw([(1, 1)], 1)
        self.assertEquals(result, [(1, 1)])

    def test_returns_none_when_have_none(self):
        available_notes = None
        result = withdraw(available_notes, 1)
        self.assertEquals(result, [])

    def test_return_highest_note_that_matches_value(self):
        available_notes = [(10, 1), (5, 2)]
        result = withdraw(available_notes, 10)
        self.assertEquals(result, [(10, 1)])
        result = withdraw(available_notes, 5)
        self.assertEquals(result, [(5, 1)])

    def test_join_two_of_the_same(self):
        available_notes = [(10, 2), (5, 2)]
        result = withdraw(available_notes, 20)
        self.assertEquals(result, [(10, 2)])
