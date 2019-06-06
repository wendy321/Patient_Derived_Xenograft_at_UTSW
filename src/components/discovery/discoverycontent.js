"use strict";

var React = require('react');
var TreeFilter = require('./treefilter');
var Query = require('./query');
var FilterStore = require('../../stores/filterStore');
var initialState = ({
    selectedFilters: [],
    result: {
        num: {patientsNum: null, sampleNum: null},
        pie: {
            gender: {female: null, male: null, unknown: null},
            ethnicity: {'hispanic': null, 'non_hispanic': null, 'white': null, 'unknown': null},
            race: {'african_american': null, 'african_american_and_white': null, 'asian': null, 'hispanic': null, 'white': null, 'other': null, 'unknown': null},
            age: {'0-1 yr': null, '2-5 yrs': null, '6-10 yrs': null, '11-15 yrs': null, '16-20 yrs': null, '>= 21 yrs': null, unknown: null},
            finalDiagnosis: {oral: null, peritoneum: null, respiratory: null, bone: null, genitourinary: null, lymphatic: null, other: null, unknown: null},
            therapy: {'chemotherapy': null, 'chemotherapy_and_radiation': null, 'no_treatment': null, 'unknown': null},
            primaryTumorSite: {'abdomen': null, 'bone marrow/blood': null, 'brain': null, 'brain - left frontal lobe': null, 'brain - right frontal lobe': null,
                'brain - left parieto-occipital lobes': null, 'cerebellume': null, 'clavicle': null, 'left adrenal gland': null, 'left cerebellum': null, 'left chest': null, 'left chest wall - left frontal lobe': null, 'left femur': null,
                'left kidney': null, 'left lymph node': null, 'left lymph node neck': null, 'left neck/lymph node': null, 'left ovary': null, 'left parotid': null,
                'left pleural cavity': null, 'left pleural cavity and left lung': null, 'left posterior mediastinum': null, 'left proximal femur': null, 'left retroperitoneum': null, 'left rib': null,
                'left testis': null, 'liver': null, 'mediastinum': null, 'mesenteric lymph node': null, 'omentum': null, 'pelvis': null,
                'posterior fossa': null, 'right femur': null, 'right foot': null, 'right kidney': null, 'right kidney and adrenal gland': null, 'right ovary': null,
                'right parotid gland': null, 'right proximal humerus': null, 'right testicle': null, 'right testis': null, 'right tibia': null, 'scalp': null,
                'unknown': null},
            primaryRelapse: {primary: null, relapse: null, unknown: null},
            procedureType: {'amputation': null, 'aspirate': null, 'autopsy': null, 'core': null, 'excisional': null, 'leukophoresis': null, 'marrow aspirate': null, 'resection': null, 'unknown': null},
            hasPDXDna: {yes: null, no: null, unknown: null},
            hasPDXRna: {yes: null, no: null, unknown: null},
            hasPrimaryDna: {yes: null, no: null, unknown: null},
            hasPrimaryRna: {yes: null, no: null, unknown: null}
        }
    }
});

var DiscoveryContent = React.createClass({
    getInitialState: function(){
        return initialState;
    },
    componentWillMount: function(){
        FilterStore.addClickListener(this._onClick);
    },
    componentDidMount: function(){
        if(this.isMounted()){
            this.setState({selectedFilters: FilterStore.getAllFilters(), result: FilterStore.getAllResult()});
        }
    },
    componentWillUnmount: function(){
        FilterStore.removeClickListener(this._onClick);
    },
    _onClick: function() {
        this.setState({selectedFilters: FilterStore.getAllFilters(), result: FilterStore.getAllResult()});
    },
    render: function() {
        return (
            <div; className="row">
            <TreeFilter />
                <Query; selectedFilters={this.state.selectedFilters} queryResult={this.state.result}/>
            </div>;
        )
    }
});

module.exports = DiscoveryContent;