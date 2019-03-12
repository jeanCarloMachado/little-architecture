import unittest


class Gateway():
    def load_available_notes():
        raise Exception("implement me")


class ATM():
    def __init__(self, gateway):
        self._gateway = gateway

    def withdraw(self, amount: int):
        return ATM._withdraw(self._gateway.load_available_notes(), amount)

    @staticmethod
    def _withdraw(availble_notes, amount: int):
        """ available notes is a datastructture of List[(note, quantityAvailable)] - sorted by bigger note to smaller """

        result = []
        rest = amount
        for note, quantity_available in availble_notes:
            if rest >= note:
                number_of_notes = ATM._get_number_of_notes_minor_than_amount(rest, note)
                if number_of_notes > quantity_available:
                    number_of_notes = quantity_available

                rest -= (note * number_of_notes)
                result.append((note, number_of_notes))

        return result

    @staticmethod
    def _get_number_of_notes_minor_than_amount(amount, note):
        number_of_notes = 1
        while amount >= (note * (number_of_notes + 1)):
            number_of_notes += 1

        return number_of_notes


class TestAtm(unittest.TestCase):
    def test_user_asks_exact_amount(self):
        result = ATM._withdraw(availble_notes=[(1, 1)], amount=1)
        self.assertEqual(result, [(1, 1)])

        result = ATM._withdraw(availble_notes=[(5, 1)], amount=5)
        self.assertEqual(result, [(5, 1)])

    def test_user_asks_5_and_the_bank_has_none_shoud_return_none(self):
        result = ATM._withdraw(availble_notes=[], amount=5)
        self.assertEqual(result, [])

    def test_use_more_than_one_of_the_same(self):
        result = ATM._withdraw(availble_notes=[(10, 2)], amount=20)
        self.assertEqual(result, [(10, 2)])

    def test_aggregate_price_with_different_notes(self):
        result = ATM._withdraw(availble_notes=[(10, 1), (5, 1)], amount=15)
        self.assertEqual(result, [(10, 1), (5, 1)])

    def test_use_different_notes_to_complete_when_bigger_unavailable(self):
        result = ATM._withdraw(availble_notes=[(10, 1), (5, 2)], amount=20)
        self.assertEqual(result, [(10, 1), (5, 2)])


class TestIngrationAtm(unittest.TestCase):
    def test_simple(self):
        gateway = Gateway()
        gateway.load_available_notes = lambda: [(1, 1)]
        atm = ATM(gateway)

        result = atm.withdraw(1)
        self.assertEqual(result, [(1, 1)])
