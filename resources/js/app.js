import './bootstrap';

// Import DataTables
import $ from 'jquery';
import 'datatables.net-bs4';

$(document).ready(function () {
  $('#mytable').DataTable();
});


//table in searching the user in the borrrowing form
   // Get the search input and users table elements
   const searchInput = document.getElementById('search-student-borrow');
   const usersTable = document.getElementById('users-table');

   // Add an event listener to the search input
   searchInput.addEventListener('input', function () {
       // Get the search term
       const searchTerm = searchInput.value.toLowerCase();

       // Loop through each row in the users table
       for (let i = 0; i < usersTable.rows.length; i++) {
           const row = usersTable.rows[i];
           const name = row.cells[0].textContent.toLowerCase();
           const email = row.cells[1].textContent.toLowerCase();

           // Check if the row matches the search term
           if (name.includes(searchTerm) || email.includes(searchTerm)) {
               row.style.display = '';
           } else {
               row.style.display = 'none';
           }
       }
   });


   //table in searching the BOOK in the borrrowing form
   // Get the search input and users table elements
   const searchInputBook = document.getElementById('search-book-toborrow');
   const BooksToBorrowTable = document.getElementById('table-book-toborrow');

   // Add an event listener to the search input
   searchInputBook.addEventListener('input', function () {
       // Get the search term
       const searchBook = searchInputBook.value.toLowerCase();

       // Loop through each row in the users table
       for (let i = 0; i < BooksToBorrowTable.rows.length; i++) {
           const rowBook = BooksToBorrowTable.rows[i];
           const bookTitle = rowBook.cells[0].textContent.toLowerCase();
           const bookAuthor = rowBook.cells[1].textContent.toLowerCase();
           const bookID = rowBook.cells[2].textContent.toLowerCase();


           // Check if the row matches the search term
           if (bookTitle.includes(searchBook) || bookAuthor.includes(searchBook) || bookID.includes(searchBook)) {
            rowBook.style.display = '';
           } else {
            rowBook.style.display = 'none';
           }
       }
   });



    // Get the current date
    var currentDate = new Date().toISOString().split('T')[0];

    // Set the min attribute of the input element
    document.getElementById('recipient-name').setAttribute('min', currentDate);


