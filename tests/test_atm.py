import unittest


def withdraw(availble_notes, amount: int):
    """ available notes is a datastructture of List[(note, quantityAvailable)] """
    for note, quantity in availble_notes:
        if amount == note:
            return [(note, 1)]

    return []


class TestAtm(unittest.TestCase):
    def test_user_asks_exact_amount(self):
        result = withdraw(availble_notes=[(1, 1)], amount=1)
        self.assertEqual(result, [(1, 1)])

        result = withdraw(availble_notes=[(5, 1)], amount=5)
        self.assertEqual(result, [(5, 1)])

    def test_user_asks_5_and_the_bank_has_none_shoud_return_none(self):
        result = withdraw(availble_notes=[], amount=5)
        self.assertEqual(result, [])
