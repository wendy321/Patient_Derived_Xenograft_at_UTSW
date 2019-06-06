"use strict";

var Dispatcher = require('../dispatcher/appDispatcher');
var ActionTypes = require('../constants/actionTypes');
var EventEmitter = require('events').EventEmitter;
var assign = require('object-assign');
var _ = require('lodash');
var CLICK_EVENT = 'click';

var _initialResult = {};
var _result = {};
var _filters = [];

var FilterStore = assign({}, EventEmitter.prototype, {
	addClickListener: function(callback) {
		this.on(CLICK_EVENT, callback);
	},
	removeClickListener: function(callback) {
		this.removeListener(CLICK_EVENT, callback);
	},
	emitClick: function() {
		this.emit(CLICK_EVENT);
	},
	getAllResult: function() {
		return _result;
	},
	getInitialResult: function() {
		return _initialResult;
	},
	getAllFilters: function() {
		return _filters;
	},
	getFilterById: function(id) {
		return _.find(_filters, {id: id});
	}
});

Dispatcher.register(function(action) {
	switch(action.actionType) {
		case ActionTypes.INITIALIZE:
			_filters = action.initialData.initialFilters;
			_result = action.initialData.initialResult;
			_initialResult = _result;
			FilterStore.emitClick();
			break;
		case ActionTypes.CREATE_FILTER:
			var existingFilterIndex = _.indexOf(_filters, _.find(_filters, {id: action.newFilter.id}));
			if(existingFilterIndex < 0){
				_filters.push(action.newFilter);
			}else{
				_.remove(_filters, function(filter) {
					return action.newFilter.id === filter.id;
				});
			}
			_result = action.result;
			FilterStore.emitClick();
			break;
		case ActionTypes.DELETE_FILTER:
			_.remove(_filters, function(filter) {
				return action.deletedFilterId === filter.id;
			});
			_result = action.result;
			FilterStore.emitClick();
			break;
		case ActionTypes.DELETE_ALLFILTER:
			for (var i = _filters.length; i > 0; i--) {
				_filters.pop();
			}
			_result = action.result;
			FilterStore.emitClick();
			break;
		default:
			// no op
	}
});

module.exports = FilterStore;