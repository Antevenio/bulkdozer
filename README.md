Bulkdozer: 
=========
A Predictive Email Filter

The Problem
-----------

When a software exception that needs to be notified by email occurs, it usually comes from multiple sources in parallel (workers), meaning that while the problem persists, it will result in thousands of more or less identical emails being sent. This ends up collapsing the receiver’s inbox, preventing him from being able to sort any other emails out and, on top of that, not even be able to receive them (as the service is being overflood with this error emails). 

The Solution
------------

An email interception layer should be in charge of collecting intelligence about emails and take decisions over time such as whether an email should or not be immediately sent, grouping similar emails into one and send this bulk messages at once when the bulk is considered not to be growing for a reasonable amount of time.

The Implementation
------------------
The Filter is a script run as a exim pipe (Or any other smtp server software that support pipes)
The Filter will use a cache system to temporarily store emails (redis or memcached looks good for this).
A cache system that must be able to:

> Keep a copy of a any new email for a certain amount of time *(a configurable value)*
> Store a counter for every individual email, incrementing it whenever an exact copy of it is being received
> Store "groups" of emails that look very similar *(a configurable percentage)* indexed by the very first (considered new) received email.
> Have a knowledge of when a "similar" email was last received for every "group" of emails.

A scheduled task that must be able to:

> Periodically communicate with the cache system, and send "bulks" (groups of similar emails, along with counters for identical ones) when the last received similarity happened before a certain amount of time *(a configurable value)*
