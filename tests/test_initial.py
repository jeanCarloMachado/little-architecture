import unittest
from typing import Dict, List

Note = int
Quantity = int
RequestedAmount = int


def withdraw(available_notes, amount: RequestedAmount):
    """ available_notes must be sorted from the highest value to the lowest """
   if available_notes is None:
        return []
    if amount in available_notes:
        return [(amount, 1)]

    rest_amount = amount
    user_notes = []
    for current_note, quantity in available_notes:
        if current_note <= rest_amount:
            quantity_of_notes = _get_maximum_of_notes_to_complete_value(rest_amount, current_note)
            user_notes.append((current_note, quantity_of_notes))
            rest_amount = rest_amount - (current_note * quantity_of_notes)
            print(rest_amount)

    return user_notes


def _get_maximum_of_notes_to_complete_value(value_to_cover, note_value):
    number_of_notes = 1
    while value_to_cover >= (note_value * (number_of_notes + 1)):
        number_of_notes += 1

    return number_of_notes


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

    def test_use_different_notes_to_complete_value(self):
        available_notes = [(10, 2), (5, 2)]
        result = withdraw(available_notes, 15)
        self.assertEquals(result, [(10, 1), (5, 1)])
