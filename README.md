# README for tnsparser.class

##### Last updated on 02-Jun-2016

## Introduction

This class was build to easily parse the Oracle TNS files, mainly for my
OIS2 application to have some kind of automatic detection of TNS names in
place. It is licensed under the BSD, so do what you want with it, but please
keep the copyright notice intact, thank you!

The class takes an tnsnames.ora file, parses it down to all components and
returns an associative array with all parsed data, indexed by the TNS name.

As many components inside a TNS name could be specified multiple times,
the returned array values are in fact arrays, too. Here's an example how
an resulting array may look like:

    Array
    (
        [ORADEV] => Array
            (
                [PROTOCOL] => Array
                    (
                        [0] => TCP
                    )
    
                [HOST] => Array
                    (
                        [0] => 192.168.255.2
                    )
    
                [PORT] => Array
                    (
                        [0] => 1521
                    )
    
                [SERVER] => Array
                    (
                        [0] => DEDICATED
                    )
    
                [SERVICE_NAME] => Array
                    (
                        [0] => ORADEV.LOCAL
                    )
            )
    )

This is the parsing result of the following original TNS entry:

    ORADEV =
      (DESCRIPTION =
        (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.255.2)(PORT = 1521))
        (CONNECT_DATA =
          (SERVER = DEDICATED)
          (SERVICE_NAME = ORADEV.LOCAL)
        )
      )

The distribution provides an example taken from Oracle docs with multiple
host specifications, so you can see exactly how this all works.

## Usage

Usage is rather simple, create a new instance from the class and call
the public method "ParseTNS()" with the path to your oracle tnsnames.ora
file, i.e.:

    $TNS = new TNSParser;
    $rc = $TNS->ParseTNS('/opt/oracle/product/11.2/network/admin/tnsnames.ora');
    print_r($rc);

In case of an error the method throws an exception, so you should use a
try/catch block to react on errors.


## Final words

I've successfully tested this class with a number of different and rather
complex TNSnames files, however no software is really bugfree, so if you
find a bug or have an idea how to improve this class, either do it on your
own or feel free to contact me on my homepage, which could be found under
the following URL:

http://www.saschapfalz.de

Nothing more to say, I doubt that anyone really reads this file until
the end, so keep up coding :D
