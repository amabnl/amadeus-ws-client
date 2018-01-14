## Contributing to amadeus-ws-client

First of all, thanks for your interest in contributing to this library!

This library currently supports the most commonly used messages used in an AIR booking flow, but there are many more messages that are not yet implemented. Besides that, there are some complex messages (`PNR_AddMultiElements`, `Fare_MasterPricerTravelBoardSearch`, ...) for which not every feature has been implemented. So there is definitely plenty of work to do!

If you don't know where to contribute, there are [a few issues](https://github.com/amabnl/amadeus-ws-client/issues?q=is%3Aissue+is%3Aopen+label%3A%22help+wanted%22) which have been tagged as "help wanted". 

If you have a new message to contribute, or a feature on an existing message, your help is appreciated! Below are some guidelines to follow when implementing features/messages:

### For any Pull Requests

* The library is currently very close to 100% code coverage (in fact, all files except one are 100% covered), so your contribution **must** be tested with 100% code coverage.
* In the PR, describe the feature you are adding and for which message. If possible, provide a reference to the Amadeus Web Services Extranet example.
* Always add an usage example to the documentation. Make sure you use terms that are familiar to people who have Amadeus knowledge.
* You can also add a descriptive feature in the `CHANGELOG` under the _Unreleased_ analogous to what has been done in [this pull request](https://github.com/amabnl/amadeus-ws-client/pull/94/files#diff-4ac32a78649ca5bdd8e0ba38b7006a1e).

#### When adding features to an already supported message

* Make sure backwards compatibility is maintained when adding extra options. Usually, if you don't break any existing tests, you should be fine.
* Always provide one or more unittests using the new feature.

An example Pull request to learn from: https://github.com/amabnl/amadeus-ws-client/pull/94

#### When adding a new message

Here's a few comments explaining how one can go about implementing a new message: 
- https://github.com/amabnl/amadeus-ws-client/issues/79#issuecomment-311026268
- https://github.com/amabnl/amadeus-ws-client/issues/93#issuecomment-331443917

When adding support for a new message, it's important that you make the Request Options object *(the object users of the library will use to provide parameters to your new message)* 
as clear as possible while providing all possible functionality the message provides. That doesn't mean you have to implement every single option in the message, 
but if you implement a feature of a message, you must strive to support all use cases of that option. 
For that it's best to rely on the Amadeus Web Services Extranet functional documentation for the message. 

You should also remember that the request options object is an object used by users of this library, so we should be able to maintain backwards compatibility even when changing the features implemented for a message.

An example pull request to learn from: https://github.com/amabnl/amadeus-ws-client/pull/74