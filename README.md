<h1>The Voteomatic</h1>

https://github.com/AdamSwenson/voteomatic

# WHATIS

The Voteomatic helps faculty / academic senates and other deliberative bodies do their work. 

Watch a demo here: https://www.youtube.com/watch?v=JY6gaY3ArQE


## Features

- Completely anonymous voting

- Members authenticate through the campus learning management system. No creating passwords or figuring out who is a member. Just put all members in a Canvas/Moodle/etc course. 

- Basic parliamentary procedure built in from the ground up.

- Export the motion tree to help compile meeting minutes.

- Open source to provide transparency. See for yourself that nothing shady is going: https://github.com/AdamSwenson/voteomatic


### Authentication via LTI

Here's the problem. Asking members to create their own login credentials is a pain. It's a hassle for the administrators who have to ensure only members have joined.

Of course, everyone already has a secure login to university resources. Unfortunately, campus IT will be rightly leery of allowing a small 3rd party app use of the campus login system.

Fortunately, most modern learning management systems (e.g., Canvas, Moodle, et al) provide a secure way for students to complete assignments in 3rd party apps. Thus by creating a Canvas/Moodle/et al course containing all and only members, we can ensure the integrity of votes with minimal hassle. Everyone logs into the learning management system and then accesses the voteomatic as though it were an assignment. Problem solved.


### Anonymity
Here's more detail on the anonymity of recorded votes. 

Votes are recorded using 2 tables. The `votes` table contains 4 data fields

    motion_id : The, ahem, id of the motion being voted on. In an election, this is the id of the office.

    is_yay : If true, the vote was affirmative; if false, it was negative.

    candidate_id : In an election, the id of the candidate the vote is for.

    receipt : A random hash (string of numbers and letters) that is sent back to the voter so that they can verify their vote was recorded. It is not stored anywhere on the app; if the voter navigates away from the page where it appears, the receipt will be irretrivable. 

It also contains some administrative fields like an id and timestamp.

So far, there is no way to tell who has voted. Thus the `recorded_vote_records` table has the fields: 

    user_id : The, surprise surprise, id of the voter

    motion_id : I think you get the picture

When a vote reaches the server, the program checks this table to ensure that the user hasn't already voted. Note that this table does not contain any timestamps, to prevent attempts at correlating entries with the `votes` table. 

Feel free to review the following migration files (used to create the database tables) to verify this explanation: 

    database/migrations/2020_10_21_225349_create_votes_table.php

    database/migrations/2020_10_22_232443_create_recorded_vote_records_table.php

### Receipts

After recording your vote, you will receive a receipt (in elections, you will receive one for each office). The receipt will be a random string of characters which look like

    $2y$10$/oKZS94ZXNuLmOq9Egum0.tmX6U54MZ1Cwx4tceuYsCc/UdBw7QhK

This receipt is tied directly to your vote; only you possess it (see Anonymity above). There will be buttons which allow you to download or copy the receipts to your clipboard, 
please use them.

Once your vote is cast, you may enter the receipt on the Verify Vote tab to verify that your vote was recorded. To convince yourself that the system 
really is checking that a vote exists in the database, feel free to enter invalid receipts. 

If there is a problem where you need a system administrator to access your vote (e.g., if you accidentally selected the wrong candidate
and the election policies allow such errors to be corrected), the only way to do this is through your receipt. 


# Bugs, complaints, suggestions for improvement

I will be grateful for any suggestions on how to improve the voteomatic. Please report any issues via https://github.com/AdamSwenson/voteomatic/issues or otherwise get in touch.


## Known quirks / annoying things

- When logged in as a Chair, the user is able to do dumb things with the motion tree. To wit, when a motion has been superseded by amendments or other subsidiary motions, it will be possible for the original to be set as the active motion for everyone to vote on. This will be fixed at some point, but is low priority since it only presents problems if the Chair is making bad choices. 

- Some of the Chair-only functions for creating and editing meetings are a bit buggy. Most of these have been patched in development branches; not all the patches have been merged into the production branches. (If that sentence makes no sense, checkout some of github's tutorials and join us in the wonderful world of version control --it's not just for code!)



# Browsers

The voteomatic has been tested on :

    - Firefox (macOS; Windows user agent)

    - Chrome (macOS; Windows user agent)

    - Safari (14.0.3)

    - Safari iOS (13.1.3)

    - Microsoft Edge (User agent)




# Installation

The voteomatic is built on the Laravel framework. Installation is just a matter of pulling the repository onto a server, creating a MySQL database, and running migrations. More details forthcoming. 




# Instructions for CSUN Faculty Senate meetings

# Real version

If a secret ballot is requested during our Senate meeting, please do the following.

(1) Log into Canvas and enter the 2020-21 Faculty Senate course.

(2) Look for the module named `Senate votes`

(3) Select the assignment for the current meeting, e.g., `2021 April Senate Meeting`. You will be taken to a new page inside Canvas.

(4) Look for the button labeled `Load [meeting name ] in a new window`. NB, on some browsers, the button doesn't really look like a button, just a slightly grayer box.

(5) Click the button and wait while the voteomatic loads in a new browser window/tab. Note that you are no longer in Canvas, Dorthy.

(6) If you are quick on the draw and log in before the Chair has created the motion, you probably won't see much. When the Chair announces that the vote is ready, refresh your browser.
You may refresh your browser by using one of the blue buttons marked `Refresh` or by clicking the button in your browser's toolbar. You do not need to go back into Canvas (though it is fine to relaunch the app from Canvas).

(7) When the vote is ready, you should see a yellow button marked `Vote`. Click that or select the `Vote` tab at the top of the page.

(8) Cast your vote using the green and red buttons.

(9) (optional) Copy the receipt that appears after your vote is recorded and paste into a document. It will not be accessible after you leave the vote page.

(10) When the Chair announces that the vote has been closed, refresh your browser to view the results.

You may do (1)-(6) at anytime; though if you do them too long before the vote, you may need to repeat them to relaunch the app from inside Canvas.



## Demonstration version

To help Senators familiarize themselves with the app, I've created a demonstration version which is accessed through Canvas.

Each time you log in to the demonstration version, the app creates a new meeting with exactly one member: you. Thus you may explore the app without affecting what other users see.

Because each new meeting is created from the same template, it will appear as though you can vote multiple times on the same motion by logging in repeatedly. This is an illusion. Each time you log in, you are in a different meeting.

The Demonstration module in Canvas contains 2 assignments. One logs you in as the Chair of the meeting; the other as an ordinary member.


### Chair

#### What you will see

As the Chair you will be able to do fun (?) stuff like creating motions, ending voting, and viewing the vote count.

#### How to see it

To experience the awesome power of the Chair, please do the following:

(1) Log into Canvas and enter the 2020-21 Faculty Senate course.

(2) Look for the module named `Demonstration`

(3) Select the assignment named `Voting app demo: Chair`. You will be taken to a new page inside Canvas.

(4) Look for the button labeled `Load Voting app demo: Chair in a new window`. NB, on some browsers, the button doesn't really look like a button, just a slightly grayer box.

(5) Click the button and wait while the voteomatic loads in a new browser window/tab. Note that you are no longer in Canvas, Dorthy. This may take a few seconds, since the server is creating a new meeting full of votes and motions for you.

(6) You should see a yellow button marked `Vote`. Click that or select the `Vote` tab at the top of the page. f

(7) Cast your vote on the important curricular matter using either the Aye or Nay button.

(8) Go back to the `Home` tab and click the red button marked `End voting`

(9) Click the yellow button marked `Results` to view the vote count.

(10) Play around with the app, if you are so inclined.



### Member

#### What you will see

Once you are logged in to the voteomatic as an ordinary user, you will only be able to vote on the currently pending motion and verify the receipt for your vote.

In a real meeting you would also be able to see the results once the Chair ends voting. However, since there is no Chair for this demonstration meeting, you won't be able to see the results. Please check out the Chair version to see what the results look like.


#### How to see it

To see what an ordinary member will see during a meeting, please do the following:

(1) Log into Canvas and enter the 2020-21 Faculty Senate course.

(2) Look for the module named `Demonstration`

(3) Select the assignment named `Voting app demo: Member`. You will be taken to a new page inside Canvas.

(4) Look for the button labeled `Load Voting app demo: Member in a new window`. NB, on some browsers, the button doesn't really look like a button, just a slightly grayer box.

(5) Click the button and wait while the voteomatic loads in a new browser window/tab. Note that you are no longer in Canvas, Dorthy. This may take a few seconds, since the server is creating a new meeting full of votes and motions for you.

(6) You should see a yellow button marked `Vote`. Click that or select the `Vote` tab at the top of the page. f

(7) Cast your vote on the important curricular matter using either the Aye or Nay button. 

