# Little Architecture hands-on

This is a hands on talk about the little architecture.
This talk is heavily inspired by Uncle's bob post titled [A Little Architecture](https://blog.cleancoder.com/uncle-bob/2016/01/04/ALittleArchitecture.html).


How this repo is organized?

branches [version1](https://github.com/jeanCarloMachado/little-architecture/tree/version1), [version2](https://github.com/jeanCarloMachado/little-architecture/tree/version2) to versionN contain the actual implementation of the problem.

## Duration: 20 mins - Topics covered

- How to setup an application from the scratch
- How to use TDD, OOP, FP, AOP and SP
- The gateway pattern and why it matters
- Evolutionary 


## Problem space

We are creating a bank.

And the first requirement we need to cover is to offer the proper notes
when a user wants to withdraw money.

### Scenario:

- The user inputs a quantity of money he wants to withdraw in the ATM.
- The ATM provides the money with the notes it has available

    **Example**:

    If the user requests to withdraw 55 and the bank has no 50 note but has 5 notes of ten
    and a lot of notes of 5 then the machine should return 5 notes of 10 and 1 note of 5.

## Solution space

First problem solved:

1. 1 note 1 result

	- Simplify the problem, first start assuming a endless amount of notes.

2. retrieve notes composing price with different ones & support quantity available

	- The code might not even be beautiful, the refactoring step of TDD is optional.
	- The important part is convey the user requirements in the unit-test.

3. integration test, create interface
	- Have a contract to follow in your contract which is described in your programming language and conveys your domain
	- The users perspective

4. Add functional test & Provide 2 types of storage
	- Have end-to-end testing


### Summary

	Relatively simple things can tolerate a certain level of
	disorganization. However, as complexity increases, disorganization
	becomes suicidal.

	Robert Martin
	
	
	It is better to have 100 functions operate on one
	data structure than 10 functions on 10 data structures.

	Alan Perlis


- We don't want to go too deep in the unit testing code. If we stay at the business requirement level we can refactor the code. Don't test code.
- The best architect makes decisions about architecture able to be delayed
- We don't want low level policy infest higher level policy
- There's no 1 paradigm, they complement one another

See coverage.

