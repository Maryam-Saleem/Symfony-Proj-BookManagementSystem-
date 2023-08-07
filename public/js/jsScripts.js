const table=document.createElement("table");
const removePreviousTable = (container) => {
    const table = container.querySelector("table");
    console.log("Line5");
    if (table &&  table.parentElement===container) {
        console.log("tablefoundfull");
        container.removeChild(table);
    }
    else{

    }
};

const handleDeleteButtonClick = (event, bookId,ButtonText) => {


    //event.preventDefault(); // Prevent the default behavior of the button click (navigation)
    if(ButtonText==="Delete")
    {

        DeleteBook(bookId).then(r => {});
    }
    if(ButtonText==="Order")
    {
        OrderBook(bookId).then(r => {});
    }


};
const createTableFromJson=(jsonData) =>{
    const headerRow = document.createElement("tr");

    // Create table headers
    for (const key in jsonData[0]) {
        console.log("line9");
        const th = document.createElement("th");
        th.textContent = key.replace(": ", "");
        headerRow.appendChild(th);
    }
    table.appendChild(headerRow);

    // Create table rows
    jsonData.forEach((item) => {
        const row = document.createElement("tr");
        for (const key in item) {
            const td = document.createElement("td");
            td.textContent = item[key];
            row.appendChild(td);
        }
        table.appendChild(row);
    });

    return table;
}

const createTableWithButton=(jsonData, ButtonText) =>{
    const headerRow = document.createElement("tr");
    // Create table headers
    console.log("Table Creation");
    for (const key in jsonData[0]) {
        console.log("line9");
        const th = document.createElement("th");
        th.textContent = key.replace(": ", "");
        headerRow.appendChild(th);
    }

    const th = document.createElement("th");
    th.innerHTML = 'Edit';
    headerRow.appendChild(th);
    table.appendChild(headerRow);

    // Create table rows
    jsonData.forEach((item) => {
        var id = null;
        const row = document.createElement("tr", );

        for (const key in item) {
            const td = document.createElement("td");
            td.textContent = item[key];
            row.appendChild(td);
            if (key ==='id: ') {
                id = item[key];
            }
        }
        let editCell = row.insertCell();
        const editButton = document.createElement("button");
        editButton.textContent = ButtonText;
        row.setAttribute("id", id);
        if(ButtonText==="Delete")
        {
            editButton.addEventListener('click',(event)=>handleDeleteButtonClick(event,id,ButtonText))
        }
        else if(ButtonText==="Edit")
        {
            editButton.onclick = function() {
                    editBook(id).then(r => {}); // Calls the editBook function with id as an argument
            };
        }
        else if(ButtonText==="Order")
        {
            editButton.addEventListener('click',(event)=>handleDeleteButtonClick(event,id,ButtonText))
        }

        editCell.appendChild(editButton);
        table.appendChild(row);
    });

    return table;
}

//Functions:
const  showAllBooks= async () =>
{
    const response=await fetch("\showAllBooks");
    const data=await response.json();
    const showAllBooksContainer=document.getElementById("outPut");
    //removePreviousTable(showAllBooksContainer);
    const table = createTableFromJson(data);
    showAllBooksContainer.appendChild(table);
}

const  showAvailableBooks= async () =>
{
    const response=await fetch("\showAvailableBooks");
    const data=await response.json();
    const showAllBooksContainer=document.getElementById("outPut");
   // removePreviousTable(showAllBooksContainer);
    const table = createTableFromJson(data);
    showAllBooksContainer.appendChild(table);
}



const  showAvailableBooksToEdit= async () =>
{
    console.log("edit");

    const response=await fetch("\showAvailableBooks");
    const data=await response.json();
    const showAllBooksContainer=document.getElementById("outPut");
    //removePreviousTable(showAllBooksContainer);
    const ButtonLabel="Edit";
    const table = createTableWithButton(data,ButtonLabel);
    showAllBooksContainer.appendChild(table);
}

const showAvailableBooksToDelete=async()=>
{

    console.log("func called");
   const response=await fetch("\showAvailableBooks");
    const data=await response.json();
    const showAllBooksContainer=document.getElementById("outPut");
    //removePreviousTable(showAllBooksContainer);
    const ButtonLabel="Delete";
    const table = createTableWithButton(data, ButtonLabel);
    showAllBooksContainer.appendChild(table);
}
// Function to call the EditBook controller with the book id
const editBook = async (bookId) => {
    window.location.href = `/EditBook/${bookId}`;
    //window.location.href = `/EditBook`;
};


const DeleteBook = async (bookId) => {
    try {

        const response = await fetch(`/DeleteBook/${bookId}`, {
            method: 'DELETE',
        });

        if (response.ok) {
          //  console.log(response.json());

            const bookRow = document.querySelector(`tr[id="${bookId}"]`);

                       if (bookRow) {
                           console.log("bookrow@id Exists",bookId);
                           const jsonData = await response.json();
                           console.log(jsonData);
                           var rowInJsonArray = null;
                           var count = 0;
                           jsonData.forEach((item) => {
                                for (const key in item) {
                                    if(key==='id: ' && item[key]===bookId)
                                    {
                                        rowInJsonArray=count;
                                        break;
                                    }
                                }
                               count++;
                           });
                           console.log(bookRow.cells[4].textContent=jsonData[rowInJsonArray]['Quantity: ']);
                          bookRow.cells[4].textContent=jsonData[rowInJsonArray]['Quantity: '].toString();
                          bookRow.cells[3].textContent=jsonData[rowInJsonArray]['status: '].toString();

                       }
                       else {
                           // Handle the case when the server returns an error status
                           console.error('Failed to delete the book.');
                       }
        }
    }
    catch (error) {
        // Handle network errors and other exceptions
        console.error('An error occurred while deleting the book:', error);
    }
};

const OrderBook = async (bookId) => {
    try {
        console.log(bookId);
        const response = await fetch(`/OrderBook/${bookId}`, {
            method: 'POST',
        });

        if (response.ok) {
            //  console.log(response.json());
            console.log("reponse.ok");
            const bookRow = document.querySelector(`tr[id="${bookId}"]`);

            if (bookRow) {
                const jsonData = await response.json();
                console.log(jsonData);
                var rowInJsonArray = null;
                var count = 0;
                jsonData.forEach((item) => {
                    for (const key in item) {
                        if(key==='id: ' && item[key]===bookId)
                        {
                            rowInJsonArray=count;
                            break;
                        }
                    }
                    count++;
                });
                console.log(bookRow.cells[4].textContent=jsonData[rowInJsonArray]['Quantity: ']);
                bookRow.cells[4].textContent=jsonData[rowInJsonArray]['Quantity: '].toString() -1;
                bookRow.cells[3].textContent=jsonData[rowInJsonArray]['status: '].toString();

            }
            else {
                // Handle the case when the server returns an error status
                console.error('Failed to order the book.');
            }
        }
    }
    catch (error) {
        // Handle network errors and other exceptions
        console.error('An error occurred while Ordering the book:', error);
    }
};

const showAvailableBooksToBuy=async()=> {

    console.log("func called");
    const response = await fetch("\showAvailableBooks");
    const data = await response.json();
    const showAllBooksContainer = document.getElementById("outPut");
    //removePreviousTable(showAllBooksContainer);
    const ButtonLabel = "Order";
    const table = createTableWithButton(data, ButtonLabel);
    showAllBooksContainer.appendChild(table);

}

const  showOldOrdersOfSpecificUser= async () =>
{
    const response=await fetch("\OrderedBookHistory");
    const data=await response.json();
    const showAllBooksContainer=document.getElementById("outPut");
    //removePreviousTable(showAllBooksContainer);
    const table = createTableFromJson(data);
    showAllBooksContainer.appendChild(table);
}
const  showSoldBooks= async () =>
{
    const response=await fetch("\SoldBooks");
    const data=await response.json();
    if (Object.keys(data).length === 0 && data.constructor === Object) {
     console.log("Empty Response");
    }
    else{
        const showAllBooksContainer=document.getElementById("outPut");
        //removePreviousTable(showAllBooksContainer);
        const table = createTableFromJson(data);
        showAllBooksContainer.appendChild(table);
    }


}

const  viewCustomers= async () =>
{
    const response=await fetch("\ViewAllCustomers");
    const data=await response.json();
    const showAllBooksContainer=document.getElementById("outPut");
    removePreviousTable(showAllBooksContainer);
   const table = createTableFromJson(data);
   showAllBooksContainer.appendChild(table);
}


