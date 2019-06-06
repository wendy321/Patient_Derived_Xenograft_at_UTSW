"use strict";

var discoveryData = require('./discoveryData');
var _ = require('lodash');

//return cloned copy so that the item is passed by value instead of by reference
var _clone = function(item) {
    return JSON.parse(JSON.stringify(item));
};

var DiscoveryApi = {
    getAllResult: async function() {
        return await; discoveryData.getProfile()
            .then(function(result){
                return _clone(result);
            });
    },
    getAllSelectedFilters: function() {
        return _clone(discoveryData.getAllSelectedFilters());
    },
    getSelectedFilterById: function(id) {
        return _clone(_.find(discoveryData.getAllSelectedFilters(), {id: id}));
    },
    saveFilter: function(filter) {
        var allSelectedFilters = discoveryData.getAllSelectedFilters();
        var existingFilterIndex = _.indexOf(allSelectedFilters, _.find(allSelectedFilters, {id: filter.id}));
        if(existingFilterIndex < 0){
            allSelectedFilters.push(filter);
        }else{
            _.remove(allSelectedFilters, function(existFilter) {
                return filter.id === existFilter.id;
            });
        }
        return _clone(filter);
    },
    deleteFilterById: function(id) {
        var deletedFilter = _.remove(discoveryData.getAllSelectedFilters(), function(filter) {
            return filter.id === id;
        });
        return _clone(deletedFilter);
    },
    deleteAllFilters: function() {
        var allSelectedFilters = discoveryData.getAllSelectedFilters();
        for (var i = allSelectedFilters.length; i > 0; i--) {
            allSelectedFilters.pop();
        }
    }
};

module.exports = DiscoveryApi;