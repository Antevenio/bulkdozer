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
