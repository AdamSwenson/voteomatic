<h1>The Voteomatic</h1>

https://github.com/AdamSwenson/voteomatic

## WHATIS

The Voteomatic helps academic senates and other deliberative bodies do their work. 

Watch a demo here: https://www.youtube.com/watch?v=JY6gaY3ArQE


## Features

- Completely anonymous voting

- Allows members to authenticate through the LTI interface of learning management systems. No creating passwords or figuring out who is a member. Just put all members in a Canvas/Moodle/etc course. 

- Basic parliamentary procedure built in from the ground up.

- Easy export of the motion tree to aid in compiling meeting minutes.

- Open source to provide transparency. See for yourself that nothing shady is going: https://github.com/AdamSwenson/voteomatic



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

Feel free to review the following migration files (used to create the database tables) in the database/migrations folder to verify this explanation: 

    2020_10_21_225349_create_votes_table.php

    2020_10_22_232443_create_recorded_vote_records_table.php


### Authentication via LTI

Here's the problem. Asking members to create their own login credentials is a pain. It's a hassle for the administrators who have to ensure only members have joined. Of course, everyone already has a secure login to university resources. Unfortunately, campus IT will be rightly leery of allowing a small 3rd party app use of the campus login system.

Fortunately, most modern learning management systems (e.g., Canvas, Moodle, et al) provide a secure way for students to complete assignments in 3rd party apps. Thus by creating a Canvas/Moodle/et al course containing all and only members, we can ensure the integrity of votes with minimal hassle. Everyone logs into the learning management system and then accesses the voteomatic as though it were an assignment. Problem solved.


## Known quirks / annoying things

- Users will have to refresh their browsers periodically to see updated content. For example, a refresh will be required to view results once voting has ended or to see a newly made motion. I may eventually update the program to obviate this hassle. 

## Bugs, complaints, suggestions for improvement

I will be grateful for any suggestions on how to improve the voteomatic. Please report any issues via https://github.com/AdamSwenson/voteomatic/issues or otherwise get in touch. 


## Installation

The voteomatic is built on the Laravel framework. Installation is just a matter of pulling the repository onto a server, creating a MySQL database, and running migrations. More details forthcoming. 

