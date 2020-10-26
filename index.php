<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dynamic Table Generator</title>
    <meta name="description" content="Dynamic Table Generator">
    <meta name="author" content="Brand Venter">
    <style>
        .backdrop {
            background-color: rgb(52, 73, 94);
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 5px;
        }

        .body-content {
            flex-basis: 50vw;


        }

        .heading {
            text-align: center;
            flex: [ 3 ];
        }

        .heading-text {
            color: rgb(217, 203, 158);
        }

        .regTable {
            background-color: #5e3434;
            color: #BDC3C7;
        }

        .cardTable {
            background-color: #BDC3C7;
            max-width: 50vw;
            flex: max-content;

        }

        .table-full {
            border: #5e3434;
            table-layout: fixed;
            width: 100%;
            border: 1px solid;
            margin-top: 20px;
            border-collapse: collapse;
        }
    </style>
</head>
<body class="backdrop">
<div class="container">
    <section class="heading">
        <h1 class="heading-text">
            Press the Button for Table Generation
        </h1>
        <button class="regTable" id="regTable" onclick="generateTableFromServer()">Generate</button>
    </section>
</div>
<div class="container body-content">
    <section class="tableSection" id="tableSection">
        <div class="cardTable" id="cardTable">

        </div>
    </section>
</div>


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        generateTableFromServer();
    }, false);
    /*
    *
    *  Ajax function that fetches data from server and pushes it to the generateTable() func.
    * Accepts a xhr response and parses it into JSON
    * TODO: Integrate ajax call into generateTable Function
    *
    */
    function generateTableFromServer() {
        let req = new XMLHttpRequest();
        req.open("GET", '/Include/tableData.php', true);
        req.send();
        req.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let resp = JSON.parse(this.responseText);
                if (resp.respCode === 1) {
                    let inputData = resp.data;
                    generateTable(inputData[0], inputData[1], true, true); // Header Data, Row Data, Force Transpose, Force Shuffle
                } else {
                    alert("Failed to get data from Server");
                }
            } else {
                console.log("Waiting for data");
            }
        };
    }

    /*
    *
    * This function is meant to be the primary controlling function but its still sharing some responsibility with generateTableFromServer() function
    *
    * * Parameters: (Head Row Array, Body Rows Array, Force Transpose Boolean, Force Shuffle Boolean)
    *
    * */


    function generateTable(tableHeaderData, tableRowData, force_transpose, force_shuffle) {
        let tableData = transposeDataIntoRow(tableHeaderData, tableRowData, force_transpose);
        if (force_shuffle === true) {
            tableData = shuffleTableData(tableData[0], tableData[1]);
        }
        buildVisualTable(tableData[0], tableData[1]);
    }

    /*
    *
    * This function is used to transpose the data from [1,1,1],[2,2,2],[3,3,3] format to: [1,2,3],[1,2,3],[1,2,3]
    * The rowData is the most important here as its the only thing being processed. I'll probably adjust it a bit to better reflect this in the future.
    * Parameters: (Head Row Array, Body Rows Array, Force Transpose Boolean)
    * */

    function transposeDataIntoRow(headerData, rowData, force_transpose) {
        let newArray = [0, 1];
        newArray[0] = headerData;
        if (force_transpose === true) {
            newArray[1] = rowData.map((_, colIndex) => rowData.map(row => row[colIndex]));
        } else {
            newArray[1] = rowData;
        }

        return newArray;
    }

    /*
    * I built the function below to randomize the Table Headers and Row Data with them.
    * I took the from the modern Fisher- Yates Algorithm:
    * https://en.wikipedia.org/wiki/Fisher%E2%80%93Yates_shuffle#The_modern_algorithm
    *
    * Parameters: (Head Row Array, Body Rows Array)
    *
    * */
    function shuffleTableData(headInput, rowInput) {
        let tableHeaders = headInput;
        let tableRows = rowInput;
        let index = headInput.length, rand, temp1, temp2, index2 = 0;
        while (index) {
            rand = Math.floor(Math.random() * index);
            --index;
            temp1 = tableHeaders[index];
            tableHeaders[index] = tableHeaders[rand];
            tableHeaders[rand] = temp1;

            for (let i = 0; i < tableRows.length; i++) {
                temp2 = tableRows[i][index];
                tableRows[i][index] = tableRows[i][rand];
                tableRows[i][rand] = temp2;
            }
            index2++
        }
        let shuffledTableData = [0, 1];
        shuffledTableData[0] = tableHeaders;
        shuffledTableData[1] = tableRows;

        return shuffledTableData;
    }

    /*
    *
    * This function is used to create the actual HTML of the Header & Row Data being given to it.
    *
    * Parameters: (Head Row Array, Body Rows Array)
    * */
    function buildVisualTable(tableHeaders, tableRows) {

//Build outer structure of the Table
        let table = document.createElement('table');
        let tableHead = document.createElement('thead');
        let tableBody = document.createElement('tbody');

//Creating the row for the table header & populating it with the headers
        let tableHeadRow = document.createElement('tr');
        tableHeaders.forEach(function (headCellData) {
            let tableHeadCell = document.createElement('th');
            tableHeadCell.appendChild(document.createTextNode(headCellData));
            tableHeadRow.appendChild(tableHeadCell);
        });
        tableHead.appendChild(tableHeadRow);
        table.appendChild(tableHead);

//Creating the rows for the table body & populating with the row data
        tableRows.forEach(function (rowData) {
            let row = document.createElement('tr');

            rowData.forEach(function (cellData) {
                let cell = document.createElement('td');
                cell.appendChild(document.createTextNode(cellData));
                row.appendChild(cell);
            });
            tableBody.appendChild(row);
        });
        table.appendChild(tableBody);

//Adding CSS Classes for some formatting
        table.className = 'table-full';
//Cleaning up Previous Table and Populating new Table
        document.getElementById('cardTable').innerHTML = '';
        document.getElementById('cardTable').appendChild(table);

    }
</script>

</body>
</html>

