<h1>The Voteomatic</h1>

## WHATIS

The Voteomatic helps academic senates and other deliberative bodies do their work. 

## Features

- Completely anonymous voting

- Allows members to authenticate through the LTI interface of learning management systems. No creating passwords or figuring out who is a member. Just put all members in a Canvas/Moodle/etc course. 

- Basic parliamentary procedure built in from the ground up.

- Easy export of the motion tree to aid in compiling meeting minutes.


### Anonymity
Here's more detail on the anonymity of recorded votes. 

Votes are recorded using 2 tables. The `votes` table contains 3 data fields

    motion_id : The, ahem, id of the motion being voted on

    is_yay : If true, the vote was affirmative; if false, it was negative

    receipt : A random hash (string of numbers and letters) that is sent back to the voter so that they can verify their vote was recorded. It is not stored anywhere on the app; if the voter navigates away from the page where it appears, the receipt will be irretrivable. 

It also contains some administrative fields like an id and timestamp.

So far, there is no way to tell who has voted. Thus the `recorded_vote_records` table has the fields: 

    user_id : The, surprise surprise, id of the voter

    motion_id : I think you get the picture

When a vote reaches the server, the program checks this table to ensure that the user hasn't already voted. Note that this table does not contain any timestamps, to prevent attempts at correlating entries with the `votes` table. 

Feel free to review the following migration files (used to create the database tables) to verify this explanation: 

    2020_10_21_225349_create_votes_table.php

    2020_10_22_232443_create_recorded_vote_records_table.php



## Installation

The voteomatic is built on the Laravel framework. Installation is just a matter of pulling the repository onto a server, creating a MySQL database, and running migrations. More details forthcoming. 

