# Cash Machine

### The Problem

Develop a solution that simulate the delivery of notes when a client does a withdraw in a cash machine.


### The basic requirements are the follow:

Always deliver the lowest number of possible notes; It's possible to get the amount requested with available notes; The client balance is infinite; Amount of notes is 10 for each note; Each withdrawal reduces the number of notes. 

Available notes $ 100,00; $ 50,00; $ 20,00 , $ 10,00

Example:

    Entry: 30.00 Result: [20.00, 10.00]

    Entry: 80.00 Result: [50.00, 20.00, 10.00]

    Entry: 125.00 Result: throw NoteUnavailableException

    Entry: -130.00 Result: throw InvalidArgumentException

    Entry: NULL Result: [Empty Set]

    Entry: 3000 Result: throw NotEnoughNotesException

### NOTES: 

- An implementation should be covered by unit tests