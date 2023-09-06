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

## Application commands

Log into the PHP command line as: `make cli`

Use the withdraw command as: `bin/console cash-machine:withdraw {amount}`

> There's a little shortcut to execute the command in one step executing: `make withdraw-cmd`

## Tests

Execute: `make test` to run unit tests