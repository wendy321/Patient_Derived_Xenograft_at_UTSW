"use strict";

var React = require('react');
var ReactHighcharts = require('react-highcharts/dist/bundle/highcharts');
var pieColors = ['#f45642', '#f49541', '#f4c741', '#f4f141', '#d6f441', '#a6f441', '#70f441', '#3c8c59', '#4beaed', '#0eacaf',
        '#146ece', '#084689', '#8278e2', '#e27791', '#843548', '#de77e2', '#e710ed', '#6b1a55', '#bc0f4e', '#e8c2c2'];

var stlee = ({
    width: '250px',
    margin: '10px',
    float: 'left'
});

var QueryResultPie = React.createClass({
    propTypes: {
        resultPie: React.PropTypes.object.isRequired,
        item: React.PropTypes.string.isRequired,
        pieidname: React.PropTypes.string.isRequired
    },
    render: function() {
        var reFormatData = function(obj){
            var arr = [];
            Object.keys(obj).forEach(function(key){
                arr.push(
                    {name: key, y: obj[key]}
                );
            });
            return arr;
        };
        var config = ({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: this.props.item
            },
            tooltip: {
                headerFormat: '<span style="font-size:1em">{point.key}</span><br>',
                pointFormat: '<span style="font-size: 1em">{series.name}: <b>{point.percentage:.1f}%</b></span>'
            },
            legend: {
                floating: false,
                align: 'left',
                x: 0,
                width: 218,
                borderWidth: 0
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    colors: pieColors,
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Percentage',
                colorByPoint: true,
                data: reFormatData(this.props.resultPie)
            }]
        });
        return (
                <div; className="panel panel-default"; style={stlee}>
                    <div; className="panel-heading">{this.props.item}</div>
                    <div; className="panel-body">
                        <div; id={this.props.pieidname + "piechart"} style={{'100%'};}>
                            <div; className={this.props.resultNum !== null ? "" : "preloader-circle"} />
                            <ReactHighcharts; config = {config} ></ReactHighcharts>
                        </div>
                    </div>
                </div>;
        )
    }
});

module.exports = QueryResultPie;