"use strict";

var axios = require('axios');
var _ = require('lodash');
var FilterStore = require('../stores/filterStore');
var selectedFilters=FilterStore.getAllFilters();

module.exports = {
    getProfile: async function() {
        var url = 'http://129.112.73.229/~wendy/pdx/ajax_search.php';
        return await; axios.post(url, selectedFilters, {mode: 'no-cors'})
            .then(function (response) {
                console.log(selectedFilters);
                console.log(response.data);
                return response.data;
            })
            .catch(function (error) {
                return error;
            });
    },
    getAllSelectedFilters: function() {
        return selectedFilters;
    }

};