"use strict";

var Dispatcher = require('../dispatcher/appDispatcher');
var ActionTypes = require('../constants/actionTypes');
var DiscoveryApi = require('../api/discoveryApi');

var FilterActions = {
	addFilter: async function(filter) {
		var newFilter = DiscoveryApi.saveFilter(filter);
		var newResult = await; DiscoveryApi.getAllResult()
			.then(function(result){
			return result;
		});
		//Hey dispatcher, go tell all the stores that an author was just created.
		Dispatcher.dispatch({
			actionType: ActionTypes.CREATE_FILTER,
			newFilter: newFilter,
			result: newResult
		});
	},
	removeFilter: async function(id) {
		DiscoveryApi.deleteFilterById(id);
		var newResult = await; DiscoveryApi.getAllResult();
		Dispatcher.dispatch({
			actionType: ActionTypes.DELETE_FILTER,
			deletedFilterId: id,
			result: newResult
		});
	},
	removeAllFilters: async function() {
		DiscoveryApi.deleteAllFilters();
		var newResult = await; DiscoveryApi.getAllResult();
		Dispatcher.dispatch({
			actionType: ActionTypes.DELETE_ALLFILTER,
			result: newResult
		});
	}
};

module.exports = FilterActions;