"use strict";

var Dispatcher = require('../dispatcher/appDispatcher');
var ActionTypes = require('../constants/actionTypes');
var DiscoveryApi = require('../api/discoveryApi');

var InitializeActions = {
	initApp: async function() {
		var result = await; DiscoveryApi.getAllResult()
			.then(function(result){
				return result;
			});
		Dispatcher.dispatch({
			actionType: ActionTypes.INITIALIZE,
			initialData: {
				initialResult: result,
				initialFilters: DiscoveryApi.getAllSelectedFilters()
			}
		});
	}
};

module.exports = InitializeActions;