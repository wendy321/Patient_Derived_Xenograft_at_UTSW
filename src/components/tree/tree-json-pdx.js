// (Jun 1 2018) Tree for Patients,  Sample tables in PDX database.
var TreeData = {
    "Data Source": {
        "Institution of Origin": [
            {"value_name": "Methodist CH", "table": "Sample", "variable": "Institution", "value": "methodist ch", "id": 1},
            {"value_name": "OSU", "table": "Sample", "variable": "Institution", "value": "osu", "id": 2},
            {"value_name": "UTH/UHS", "table": "Sample", "variable": "Institution", "value": "uth/uhs", "id": 3},
            {"value_name": "UTSW", "table": "Sample", "variable": "Institution", "value": "utsw", "id": 4},
            {"value_name": "Unknown", "table": "Sample", "variable": "Institution", "value": "NULL", "id": 5}]
    },
    "Demographics": {
        "Age": [
            {"value_name": "0 - 1 yr", "table": "Patient", "variable": "Age_Months", "value": "0-13", "id": 6},
            {"value_name": "2 - 5 yrs", "table": "Patient", "variable": "Age_Months", "value": "13-61", "id": 7},
            {"value_name": "6 - 10 yrs", "table": "Patient", "variable": "Age_Months", "value": "61-121", "id": 8},
            {"value_name": "11 - 15 yrs", "table": "Patient", "variable": "Age_Months", "value": "121-181", "id": 9},
            {"value_name": "16 - 20 yrs", "table": "Patient", "variable": "Age_Months", "value": "181-241", "id": 10},
            {"value_name": ">= 21", "table": "Patient", "variable": "Age_Months", "value": "241-300", "id": 11},
            {"value_name": "Unknown", "table": "Patient", "variable": "Age_Months", "value": "NULL", "id": 12}
        ],
        "Gender": [
            {"value_name": "Female", "table": "Patient", "variable": "Gender", "value": "f", "id": 13},
            {"value_name": "Male", "table": "Patient", "variable": "Gender", "value": "m", "id": 14},
            {"value_name": "Unknown", "table": "Patient", "variable": "Gender", "value": "NULL", "id": 15}
        ],
        "Race": [
            {"value_name": "African American", "table": "Patient", "variable": "Race", "value": "aa", "id": 16},
            {"value_name": "African American / White", "table": "Patient", "variable": "Race", "value": "aa/white", "id": 17},
            {"value_name": "Asian", "table": "Patient", "variable": "Race", "value": "asian", "id": 18},
            {"value_name": "Hispanic", "table": "Patient", "variable": "Race", "value": "hispanic", "id": 19},
            {"value_name": "White", "table": "Patient", "variable": "Race", "value": "white", "id": 20},
            {"value_name": "Other", "table": "Patient", "variable": "Race", "value": "other", "id": 21},
            {"value_name": "Unknown", "table": "Patient", "variable": "Race", "value": "NULL", "id": 22}
        ],
        "Ethnicity": [
            {"value_name": "Hispanic", "table": "Patient", "variable": "Ethnicity", "value": "hispanic", "id": 23},
            {"value_name": "Non-Hispanic", "table": "Patient", "variable": "Ethnicity", "value": "non-hispanic", "id": 24},
            {"value_name": "White", "table": "Patient", "variable": "Ethnicity", "value": "white", "id": 300},
            {"value_name": "Unknown", "table": "Patient", "variable": "Ethnicity", "value": "NULL", "id": 25}
        ]
    },
    "Diagnosis": {
        "Final Diagnosis": [
            {"value_name": "(B cell) Acute Lymphoblastic Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "(b cell) acute lymphoblastic leukemia", "id": 26},
            {"value_name": "(T cell) Acute Lymphoblastic Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "(t-cell) acute lymphoblastic leukemia", "id": 27},
            {"value_name": "Acute Lymphoblastic Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "acute lymphoblastic leukemia", "id": 28},
            {"value_name": "Acute Myeloid Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "acute myeloid leukemia", "id": 29},
            {"value_name": "Adrenal Cortical Carcinoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "adrenal cortical carcinoma", "id": 30},
            {"value_name": "AML Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "aml leukemia", "id": 31},
            {"value_name": "Anaplastic Astrocytoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "anaplastic astrocytoma", "id": 224},
            {"value_name": "Anaplastic Ganglioglioma", "table": "Sample", "variable": "FinalDiagnosis", "value": "anaplastic ganglioglioma", "id": 225},
            {"value_name": "Anaplastic Wilms", "table": "Sample", "variable": "FinalDiagnosis", "value": "anaplastic wilms", "id": 226},
            {
                "value_name": "Cellular Congenitial Mesoblastic Nephroma",
                "table": "Sample",
                "variable": "FinalDiagnosis",
                "value": "cellular congenitial mesoblastic nephroma",
                "id": 13
            },
            {"value_name": "Clear Cell Sarcoma of Kidney", "table": "Sample", "variable": "FinalDiagnosis", "value": "clear cell sarcoma of kidney", "id": 32},
            {"value_name": "Dermatofibrosarcoma Protuberans", "table": "Sample", "variable": "FinalDiagnosis", "value": "dermatofibrosarcoma protuberans", "id": 33},
            {"value_name": "Benign Lymph Node", "table": "Sample", "variable": "FinalDiagnosis", "value": "benign lymph node", "id": 34},
            {"value_name": "Diffuse Astrocytoma (Grade II)", "table": "Sample", "variable": "FinalDiagnosis", "value": "diffuse astrocytoma (grade ii)", "id": 35},
            {"value_name": "Dysgerminoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "dysgerminoma", "id": 36},
            {"value_name": "Embryonal Rhabdomyosarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "embryonal rhabdomyosarcoma", "id": 37},
            {"value_name": "Ewing's Sarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "ewing's sarcoma", "id": 38},
            {"value_name": "Fibrosarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "fibrosarcoma", "id": 39},
            {"value_name": "Hepatoblastoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "hepatoblastoma", "id": 40},
            {"value_name": "Hepatocellular Carcinoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "hepatocellular carcinoma", "id": 41},
            {"value_name": "High Grade Glioma", "table": "Sample", "variable": "FinalDiagnosis", "value": "high grade glioma", "id": 42},
            {"value_name": "High Risk Neuroblastoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "high risk neuroblastoma", "id": 43},
            {"value_name": "Hodgkin Lymphoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "hodgkin lymphoma", "id": 44},
            {"value_name": "Immature Teratoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "immature teratoma", "id": 45},
            {"value_name": "Large B Cell Lymphoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "large b cell lymphoma", "id": 46},
            {"value_name": "Leydig Cell Tumor of Ovary", "table": "Sample", "variable": "FinalDiagnosis", "value": "leydig cell tumor of ovary", "id": 47},
            {"value_name": "Lupus Erythematosus", "table": "Sample", "variable": "FinalDiagnosis", "value": "lupus erythematosuss", "id": 48},
            {"value_name": "Malignant Epitheloid Mesothelioma", "table": "Sample", "variable": "FinalDiagnosis", "value": "malignant epitheloid mesothelioma", "id": 49},
            {
                "value_name": "Malignant Peripheral Nerve Sheath Tumor",
                "table": "Sample",
                "variable": "FinalDiagnosis",
                "value": "malignant peripheral nerve sheath tumor",
                "id": 50
            },
            {"value_name": "Medulloblastoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "medulloblastoma", "id": 51},
            {"value_name": "Mixed Malignant Germ Cell Tumor", "table": "Sample", "variable": "FinalDiagnosis", "value": "mixed malignant germ cell tumor", "id": 52},
            {"value_name": "Negative for Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "negative for leukemia", "id": 53},
            {"value_name": "Nephroblastoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "nephroblastoma", "id": 54},
            {"value_name": "Neuroblastoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "neuroblastoma", "id": 55},
            {"value_name": "Osteosarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "osteosarcoma", "id": 56},
            {"value_name": "Paraganglioma", "table": "Sample", "variable": "FinalDiagnosis", "value": "paraganglioma", "id": 57},
            {"value_name": "Pediatric-Type Follicular Lymphoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "pediatric-type follicular lymphoma", "id": 58},
            {"value_name": "Pheochromocytoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "pheochromocytoma", "id": 59},
            {"value_name": "Pilocytic Astrocytoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "pilocytic astrocytoma", "id": 60},
            {"value_name": "Pineoblastoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "pineoblastoma", "id": 61},
            {"value_name": "Pleomorphic Sarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "pleomorphic sarcoma", "id": 62},
            {"value_name": "Pre B ALL Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "pre b all leukemia", "id": 63},
            {
                "value_name": "PTLD (post transplant lymphoproliferative disorder)",
                "table": "Sample",
                "variable": "FinalDiagnosis",
                "value": "ptld (post transplant lymphoproliferative disorder)",
                "id": 64
            },
            {"value_name": "Rhabdomyosarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "rhabdomyosarcoma", "id": 65},
            {"value_name": "Sarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "sarcoma", "id": 66},
            {"value_name": "Synovial Sarcoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "synovial sarcoma", "id": 67},
            {"value_name": "T Lymphoblastic Leukemia", "table": "Sample", "variable": "FinalDiagnosis", "value": "t lymphoblastic leukemia", "id": 68},
            {"value_name": "T Lymphoblastic Lymphoma", "table": "Sample", "variable": "FinalDiagnosis", "value": "t lymphoblastic lymphoma", "id": 69},
            {"value_name": "Wilms Tumor", "table": "Sample", "variable": "FinalDiagnosis", "value": "wilms tumor", "id": 70},
            {"value_name": "Unknown", "table": "Sample", "variable": "FinalDiagnosis", "value": "NULL", "id": 71}
        ]
    },
    "Therapy": {
        "Therapy (prior to PDX Collection)": [
            {"value_name": "Chemo", "table": "Sample", "variable": "TherapyPriorPDXCollection", "value": "chemo", "id": 72},
            {"value_name": "Chemo and Radiation", "table": "Sample", "variable": "TherapyPriorPDXCollection", "value": "chemo and radiation", "id": 73},
            {"value_name": "No Treatment", "table": "Sample", "variable": "TherapyPriorPDXCollection", "value": "no treatment", "id": 74},
            {"value_name": "Unknown", "table": "Sample", "variable": "TherapyPriorPDXCollection", "value": "NULL", "id": 75}
        ]
    },
    "Genomic": {
        "PDX DNA Collected": [
            {"value_name": "Yes", "table": "Sample", "variable": "IsPDXDNACollected", "value": "y", "id": 76},
            {"value_name": "No", "table": "Sample", "variable": "IsPDXDNACollected", "value": "n", "id": 77},
            {"value_name": "Unknown", "table": "Sample", "variable": "IsPDXDNACollected", "value": "NULL", "id": 78}
        ],
        "PDX RNA Collected": [
            {"value_name": "Yes", "table": "Sample", "variable": "IsPDXRNACollected", "value": "y", "id": 79},
            {"value_name": "No", "table": "Sample", "variable": "IsPDXRNACollected", "value": "n", "id": 80},
            {"value_name": "Unknown", "table": "Sample", "variable": "IsPDXRNACollected", "value": "NULL", "id": 81}
        ],
        "Primary Tumor DNA Collected": [
            {"value_name": "Yes", "table": "Sample", "variable": "IsPrimaryDNACollected", "value": "y", "id": 82},
            {"value_name": "No", "table": "Sample", "variable": "IsPrimaryDNACollected", "value": "n", "id": 83},
            {"value_name": "Unknown", "table": "Sample", "variable": "IsPrimaryDNACollected", "value": "NULL", "id": 84}
        ],
        "Primary Tumor RNA Collected": [
            {"value_name": "Yes", "table": "Sample", "variable": "IsPrimaryRNACollected", "value": "y", "id": 85},
            {"value_name": "No", "table": "Sample", "variable": "IsPrimaryRNACollected", "value": "n", "id": 86},
            {"value_name": "Unknown", "table": "Sample", "variable": "IsPrimaryRNACollected", "value": "NULL", "id": 87}
        ],
        "Germline DNA Collected": [
            {"value_name": "Yes", "table": "Sample", "variable": "IsGermlineDNACollected", "value": "y", "id": 88},
            {"value_name": "No", "table": "Sample", "variable": "IsGermlineDNACollected", "value": "n", "id": 89},
            {"value_name": "Unknown", "table": "Sample", "variable": "IsGermlineDNACollected", "value": "NULL", "id": 90}
        ]
    },
    "Germline": {
        "Germline Type": [
            {"value_name": "Blood", "table": "Sample", "variable": "GermlineType", "value": "blood", "id": 91},
            {"value_name": "Saliva", "table": "Sample", "variable": "GermlineType", "value": "saliva", "id": 92},
            {"value_name": "Unknown", "table": "Sample", "variable": "GermlineType", "value": "NULL", "id": 93}
        ]
    },
    "Sample": {
        "Primary or Relapse Tumor": [
            {"value_name": "Primary", "table": "Sample", "variable": "PriOrRelapse", "value": "primary", "id": 94},
            {"value_name": "Relapse", "table": "Sample", "variable": "PriOrRelapse", "value": "relapse", "id": 95},
            {"value_name": "Unknown", "table": "Sample", "variable": "PriOrRelapse", "value": "NULL", "id": 96}
        ],
        "Procedure Type": [
            {"value_name": "Amputation", "table": "Sample", "variable": "Biospy", "value": "amputation", "id": 97},
            {"value_name": "Aspirate", "table": "Sample", "variable": "Biospy", "value": "aspirate", "id": 98},
            {"value_name": "Autopsy", "table": "Sample", "variable": "Biospy", "value": "autopsy", "id": 99},
            {"value_name": "Core", "table": "Sample", "variable": "Biospy", "value": "core", "id": 100},
            {"value_name": "Excisional", "table": "Sample", "variable": "Biospy", "value": "excisional", "id": 101},
            {"value_name": "Leukophoresis", "table": "Sample", "variable": "Biospy", "value": "leukophoresis", "id": 102},
            {"value_name": "Marrow Aspirate", "table": "Sample", "variable": "Biospy", "value": "marrow aspirate", "id": 103},
            {"value_name": "Resection", "table": "Sample", "variable": "Biospy", "value": "resection", "id": 104},
            {"value_name": "Unknown", "table": "Sample", "variable": "Biospy", "value": "NULL", "id": 105}
        ],
        "Specimen Type": [
            {"value_name": "Flash Frozen", "table": "Sample", "variable": "SpecimenType", "value": "flash frozen", "id": 106},
            {"value_name": "Frozen with OCT", "table": "Sample", "variable": "SpecimenType", "value": "frozen with oct", "id": 107},
            {"value_name": "FFPE", "table": "Sample", "variable": "SpecimenType", "value": "ffpe", "id": 108},
            {"value_name": "Fresh", "table": "Sample", "variable": "SpecimenType", "value": "fresh", "id": 109},
            {"value_name": "Other", "table": "Sample", "variable": "SpecimenType", "value": "other", "id": 110},
            {"value_name": "Unknown", "table": "Sample", "variable": "SpecimenType", "value": "NULL", "id": 111}
        ]
    },
    "Tumor": {
        "Orignial Tumor Collected": [
            {"value_name": "Yes", "table": "Sample", "variable": "IsOriginalTumorCollected", "value": "y", "id": 112},
            {"value_name": "No", "table": "Sample", "variable": "IsOriginalTumorCollected", "value": "n", "id": 113},
            {"value_name": "Unknown", "table": "Sample", "variable": "IsOriginalTumorCollected", "value": "NULL", "id": 114}
        ],
        "Primary or Metastasis Tumor Collected": [
            {"value_name": "Primary", "table": "Sample", "variable": "PrimaryOrMetTumorCollected", "value": "primary", "id": 115},
            {"value_name": "Metastasis", "table": "Sample", "variable": "PrimaryOrMetTumorCollected", "value": "met", "id": 116},
            {"value_name": "Unknown", "table": "Sample", "variable": "PrimaryOrMetTumor", "value": "NULL", "id": 117}
        ],
        "Tumor Site Collected": [
            {"value_name": "Abdomen", "table": "Sample", "variable": "CollectionTumorSite", "value": "Abdomen", "id": 118},
            {"value_name": "Bone Marrow/Blood", "table": "Sample", "variable": "CollectionTumorSite", "value": "bone marrow/blood", "id": 119},
            {"value_name": "Brain", "table": "Sample", "variable": "CollectionTumorSite", "value": "Brain", "id": 120},
            {"value_name": "Brain - left frontal lobe", "table": "Sample", "variable": "CollectionTumorSite", "value": "Brain- left frontal lobe", "id": 121},
            {"value_name": "Brain - right frontal lobe", "table": "Sample", "variable": "CollectionTumorSite", "value": "Brain- right frontal lobe", "id": 122},
            {"value_name": "Brain - left parieto-occipital lobes", "table": "Sample", "variable": "CollectionTumorSite", "value": "Brain, left parieto-occipital lobes", "id": 123},
            {"value_name": "Cerebellume", "table": "Sample", "variable": "CollectionTumorSite", "value": "cerebellum", "id": 124},
            {"value_name": "Clavicle", "table": "Sample", "variable": "CollectionTumorSite", "value": "clavicle", "id": 125},
            {"value_name": "Left Adrenal Gland", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left Adrenal Gland", "id": 126},
            {"value_name": "Left Cerebellum", "table": "Sample", "variable": "CollectionTumorSite", "value": "left cerebellum", "id": 127},
            {"value_name": "Left Chest", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left Chest", "id": 128},
            {"value_name": "Left Chest Wall", "table": "Sample", "variable": "CollectionTumorSite", "value": "left chest wall", "id": 129},
            {"value_name": "Left Femur", "table": "Sample", "variable": "CollectionTumorSite", "value": "left femur", "id": 130},
            {"value_name": "Left Kidney", "table": "Sample", "variable": "CollectionTumorSite", "value": "left kidney", "id": 131},
            {"value_name": "Left Lymph Node", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left Lymph node", "id": 132},
            {"value_name": "Left Lymph Node Neck", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left lymph node neck", "id": 133},
            {"value_name": "Left Mandible", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left Mandible", "id": 134},
            {"value_name": "Left Neck/Lymph Node", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left Neck/lymph node", "id": 135},
            {"value_name": "Left Ovary", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left ovary", "id": 136},
            {"value_name": "Left Parotid", "table": "Sample", "variable": "CollectionTumorSite", "value": "left parotid", "id": 137},
            {"value_name": "Left Pleural Cavity", "table": "Sample", "variable": "CollectionTumorSite", "value": "left pleural cavity", "id": 138},
            {"value_name": "Left Pleural Cavity and Left Lung", "table": "Sample", "variable": "CollectionTumorSite", "value": "left pleural cavity and left lung", "id": 139},
            {"value_name": "Left Posterior Mediastinum", "table": "Sample", "variable": "CollectionTumorSite", "value": "left posterior mediastinum", "id": 140},
            {"value_name": "Left Proximal Femur", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left proximal femur", "id": 141},
            {"value_name": "Left Retroperitoneum", "table": "Sample", "variable": "CollectionTumorSite", "value": "left retroperitoneum", "id": 142},
            {"value_name": "Left Rib", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left rib", "id": 143},
            {"value_name": "Left Testis", "table": "Sample", "variable": "CollectionTumorSite", "value": "Left testis", "id": 144},
            {"value_name": "Liver", "table": "Sample", "variable": "CollectionTumorSite", "value": "Liver", "id": 145},
            {"value_name": "Mediastinum", "table": "Sample", "variable": "CollectionTumorSite", "value": "Mediastinum", "id": 146},
            {"value_name": "Mesenteric Lymph Node", "table": "Sample", "variable": "CollectionTumorSite", "value": "Mesenteric lymph node", "id": 147},
            {"value_name": "Omentum", "table": "Sample", "variable": "CollectionTumorSite", "value": "Omentum", "id": 148},
            {"value_name": "Pelvis", "table": "Sample", "variable": "CollectionTumorSite", "value": "Pelvis", "id": 149},
            {"value_name": "Posterior Fossa", "table": "Sample", "variable": "CollectionTumorSite", "value": "posterior fossa", "id": 150},
            {"value_name": "Right Femur", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right femur", "id": 151},
            {"value_name": "Right Foot", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right foot", "id": 152},
            {"value_name": "Right Kidney", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right kidney", "id": 153},
            {"value_name": "Right Kidney and Adrenal Gland", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right kidney and adrenal gland", "id": 154},
            {"value_name": "Right Ovary", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right ovary", "id": 155},
            {"value_name": "Right Parotid Gland", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right Parotid Gland", "id": 156},
            {"value_name": "Right Proximal Humerus", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right proximal humerus", "id": 157},
            {"value_name": "Right Testis", "table": "Sample", "variable": "CollectionTumorSite", "value": "Right Testis", "id": 159},
            {"value_name": "Right Tibia", "table": "Sample", "variable": "CollectionTumorSite", "value": "right tibia", "id": 160},
            {"value_name": "Scalp", "table": "Sample", "variable": "CollectionTumorSite", "value": "Scalp", "id": 161},
            {"value_name": "Spinal Cord", "table": "Sample", "variable": "CollectionTumorSite", "value": "Spinal Cord", "id": 162},
            {"value_name": "Thorax", "table": "Sample", "variable": "CollectionTumorSite", "value": "Thorax", "id": 163},
            {"value_name": "Unknown", "table": "Sample", "variable": "CollectionTumorSite", "value": "NULL", "id": 164}
        ],
        "Primary Tumor Site": [
            {"value_name": "Abdomen", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Abdomen", "id": 165},
            {"value_name": "Bone Marrow/Blood", "table": "Sample", "variable": "PrimaryTumorSite", "value": "bone marrow/blood", "id": 166},
            {"value_name": "Brain", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Brain", "id": 167},
            {"value_name": "Brain - left frontal lobe", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Brain- left frontal lobe", "id": 168},
            {"value_name": "Brain - right frontal lobe", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Brain- right frontal lobe", "id": 169},
            {"value_name": "Brain - left parieto-occipital lobes", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Brain, left parieto-occipital lobes", "id": 170},
            {"value_name": "Cerebellume", "table": "Sample", "variable": "PrimaryTumorSite", "value": "cerebellum", "id": 171},
            {"value_name": "Clavicle", "table": "Sample", "variable": "PrimaryTumorSite", "value": "clavicle", "id": 172},
            {"value_name": "Left Adrenal Gland", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left Adrenal Gland", "id": 173},
            {"value_name": "Left Cerebellum", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left cerebellum", "id": 174},
            {"value_name": "Left Chest", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left Chest", "id": 175},
            {"value_name": "Left Chest Wall", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left chest wall", "id": 176},
            {"value_name": "Left Femur", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left femur", "id": 177},
            {"value_name": "Left Kidney", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left kidney", "id": 178},
            {"value_name": "Left Lymph Node", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left lymph node", "id": 179},
            {"value_name": "Left Lymph Node Neck", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left lymph node neck", "id": 180},
            {"value_name": "Left Ovary", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left ovary", "id": 182},
            {"value_name": "Left Parotid", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left parotid", "id": 183},
            {"value_name": "Left Pleural Cavity", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left pleural cavity", "id": 184},
            {"value_name": "Left Pleural Cavity and Left Lung", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left pleural cavity and left lung", "id": 185},
            {"value_name": "Left Posterior Mediastinum", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left posterior mediastinum", "id": 186},
            {"value_name": "Left Proximal Femur", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left proximal femur", "id": 187},
            {"value_name": "Left Retroperitoneum", "table": "Sample", "variable": "PrimaryTumorSite", "value": "left retroperitoneum", "id": 188},
            {"value_name": "Left Rib", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left rib", "id": 189},
            {"value_name": "Left Testis", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Left testis", "id": 190},
            {"value_name": "Liver", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Liver", "id": 191},
            {"value_name": "Mediastinum", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Mediastinum", "id": 192},
            {"value_name": "Mesenteric Lymph Node", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Mesenteric lymph node", "id": 193},
            {"value_name": "Omentum", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Omentum", "id": 194},
            {"value_name": "Pelvis", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Pelvis", "id": 195},
            {"value_name": "Posterior Fossa", "table": "Sample", "variable": "PrimaryTumorSite", "value": "posterior fossa", "id": 196},
            {"value_name": "Right Femur", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right femur", "id": 197},
            {"value_name": "Right Foot", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right foot", "id": 198},
            {"value_name": "Right Kidney", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right kidney", "id": 199},
            {"value_name": "Right Kidney and Adrenal Gland", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right kidney and adrenal gland", "id": 200},
            {"value_name": "Right Ovary", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right ovary", "id": 201},
            {"value_name": "Right Parotid Gland", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right Parotid Gland", "id": 202},
            {"value_name": "Right Proximal Humerus", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right proximal humerus", "id": 203},
            {"value_name": "Right Testis", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Right Testis", "id": 205},
            {"value_name": "Right Tibia", "table": "Sample", "variable": "PrimaryTumorSite", "value": "right tibia", "id": 206},
            {"value_name": "Scalp", "table": "Sample", "variable": "PrimaryTumorSite", "value": "Scalp", "id": 207},
            {"value_name": "Unknown", "table": "Sample", "variable": "PrimaryTumorSite", "value": "NULL", "id": 208}
        ],
        "Metastatic Tumor Site": [
            {"value_name": "Clavicle and Thorax", "table": "Sample", "variable": "MetSite", "value": "clavicle and thorax", "id": 209},
            {"value_name": "Jaw and Left Clavicle", "table": "Sample", "variable": "MetSite", "value": "jaw and left clavicle", "id": 210},
            {"value_name": "Leptomeningeal Metastasis", "table": "Sample", "variable": "MetSite", "value": "leptomeningeal metastasis", "id": 211},
            {"value_name": "Liver and Lymph Node", "table": "Sample", "variable": "MetSite", "value": "liver and lymph node", "id": 212},
            {"value_name": "Lung", "table": "Sample", "variable": "MetSite", "value": "lung", "id": 213},
            {"value_name": "Lymph Node", "table": "Sample", "variable": "MetSite", "value": "lymph node", "id": 214},
            {"value_name": "Spinal Cord", "table": "Sample", "variable": "MetSite", "value": "spinal cord", "id": 215},
            {"value_name": "Unknown", "table": "Sample", "variable": "MetSite", "value": "NULL", "id": 216}
        ],
        "Metastatic Tumor Laterality": [
            {"value_name": "Left", "table": "Sample", "variable": "MetLaterality", "value": "left", "id": 217},
            {"value_name": "Right", "table": "Sample", "variable": "MetLaterality", "value": "right", "id": 218},
            {"value_name": "Left and Right", "table": "Sample", "variable": "MetLaterality", "value": "right and left", "id": 219},
            {"value_name": "Unknown", "table": "Sample", "variable": "MetLaterality", "value": "NULL", "id": 220}
        ],
        "Metastatic at Diagnosis": [
            {"value_name": "Yes", "table": "Sample", "variable": "MetaDiagnosis", "value": "yes", "id": 221},
            {"value_name": "No", "table": "Sample", "variable": "MetaDiagnosis", "value": "no", "id": 222},
            {"value_name": "Unknown", "table": "Sample", "variable": "MetaDiagnosis", "value": "NULL", "id": 223}
        ]
    }
};

module.exports = TreeData;