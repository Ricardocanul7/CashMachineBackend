# Cash Machine

## Requirements

- (Windows) Install WSL2
- Latest version of docker
- Install make package to run makefile rules
    - In Windows can be installed in WSL2 as `sudo apt-get install make`


## Installation

Execute the next command in the terminal which will create the environment and install all required packages automatically:

```
make install
```


> In case you need to restart the dev environment, execute: `make reset-dev` which will destroy the environment and rebuilt it.

## Usage

To turn on the environment: `make docker-up`


To turn off the environment: `make docker-down`

### Introduction

When the application starts it's automatically filled with

- 10 notes of $10
- 10 notes of $20
- 10 notes of $50
- 10 notes of $100

The withdraw machine will give you back notes of specific values depending on the day of the week

| Day of the Week | Notes Order |
| --- | --- |
| Monday | Highest to lowest |
| Tuesday | Highest to lowest |
| Wednesday | Highest to lowest |
| Thursday | Highest to lowest |
| Friday | Highest to lowest |
| Saturday | Lowest to highest |
| Sunday | Lowest to highest |

## Application commands

Log into the PHP command line as: `make cli`

Use the withdraw command as: `bin/console cash-machine:withdraw {amount} {date}`

> Date format (YYYY-MM-DD)

## Examples


### Example 1
> bin/console cash-machine:withdraw 160 2025-06-15

Output: [10,10,10,10,10,10,10,10,10,10,20,20,20]

As 2025-06-19 represents the day Sunday, so it will return notes with the lowest values first

### Example 2

> bin/console cash-machine:withdraw 160 2025-06-20

Output: [100,50,10]

As 2025-06-19 represents the day Friday, so it will return notes with the highes values first

### Example 3

> bin/console cash-machine:withdraw 1810 2025-06-20

Not enough notes

As the maximun amount of notes are 1800 you will get `Not enough notes`


## Tests

Execute: `make test` to run unit tests