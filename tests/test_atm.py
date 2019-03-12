import unittest


def withdraw(availble_notes, amount: int):
    """ available notes is a datastructture of List[(note, quantityAvailable)] - sorted by bigger note to smaller """

    rest = amount
    result = []
    for note, quantity in availble_notes:
        if rest >= note:
            number_of_notes = _get_number_of_notes_minor_than_amount(amount, note)
            result.append((note, number_of_notes))
            rest -= note * number_of_notes

    return result


def _get_number_of_notes_minor_than_amount(amount, note):
    number_of_notes = 1
    while amount >= (note * (number_of_notes + 1)):
        number_of_notes += 1

    return number_of_notes


class TestAtm(unittest.TestCase):
    def test_user_asks_exact_amount(self):
        result = withdraw(availble_notes=[(1, 1)], amount=1)
        self.assertEqual(result, [(1, 1)])

        result = withdraw(availble_notes=[(5, 1)], amount=5)
        self.assertEqual(result, [(5, 1)])

    def test_user_asks_5_and_the_bank_has_none_shoud_return_none(self):
        result = withdraw(availble_notes=[], amount=5)
        self.assertEqual(result, [])

    def test_use_more_than_one_of_the_same(self):
        result = withdraw(availble_notes=[(10, 2)], amount=20)
        self.assertEqual(result, [(10, 2)])

    # def test_aggregate_price_with_different_notes(self):
    #     result = withdraw(availble_notes=[(10, 1), (5, 1)], amount=15)
    #     self.assertEqual(result, [(10, 1), (5, 1)])
