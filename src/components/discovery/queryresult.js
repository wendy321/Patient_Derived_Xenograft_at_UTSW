"use strict";

var React = require('react');
var QueryResultNum = require('./queryresultnum');
var QueryResultPie = require('./queryresultpie');
var NumVariables = [{item: 'Patients', classname: 'patients', iconClassName: 'fa fa-user'},
					{item: 'Samples', classname: 'sample', iconClassName: 'fa fa-equals'}];
var PieVariables = [{item: 'Gender', pieidname: 'gender'}, {item: 'Ethnicity', pieidname: 'ethnicity'},
					{item: 'Race', pieidname: 'race'}, {item: 'Age', pieidname: 'age'},
					{item: 'Final Diagnosis', pieidname: 'finalDiagnosis'}, {item: 'Treatment Type', pieidname: 'therapy'},
					{item: 'Primary Tumor Site', pieidname: 'primaryTumorSite'}, {item: 'Primary / Relapse', pieidname: 'primaryRelapse'},
					{item: 'Procedure Type', pieidname: 'procedureType'}, {item: 'Has PDX DNA', pieidname: 'hasPDXDna'},
					{item: 'Has PDX RNA', pieidname: 'hasPDXRna'}, {item: 'Has Primary DNA', pieidname: 'hasPrimaryDna'},
					{item: 'Has Primary RNA', pieidname: 'hasPrimaryRna'}];

var pieChartsStyle = ({
	marginTop: '10px',
	padding: 'auto'
});

var QueryResult = React.createClass({
	propTypes: {
		queryResult: React.PropTypes.object.isRequired
	},
	render: function() {
		var createQueryResultNums = function(element){
			return (
				<QueryResultNum;
					item={element.item}
					classname={element.classname}
					iconClassName={element.iconClassName}
					resultNum={this.props.queryResult.num[element.classname + 'Num']}
				/>
            )
        };
		var createQueryResultPies = function(element){
			return (
				<QueryResultPie;
					item={element.item}
					pieidname={element.pieidname}
					resultPie={this.props.queryResult.pie[element.pieidname]}
					/>
            )
        };

		return (
            <div>
                <div; className="totalnumpanel col-sm-12" >
                    {NumVariables.map(createQueryResultNums, this)}
                </div>
                <div; className="piecharts col-sm-12"; style={pieChartsStyle}>
                    {PieVariables.map(createQueryResultPies, this)}
                </div>
            </div>;
        )
    }
});

module.exports = QueryResult;