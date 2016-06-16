$(function() {
   var loadAllBooks = function() {
       var booksList = $("#listWithBooks");
       $.ajax({
           url: "http://192.168.33.22/REST_bookshelf/api/books.php",
           method: "GET",
           dataType: "JSON"
       }).done(function(bookNamesArray) {
           booksList.empty();
           for(var i = 0; i < bookNamesArray.length; i++) {
               var newLi = $("<li class='list-group-item'>");
               var removeButton = $("<button class='delBtn btn btn-danger badgeRed'>Delete</button>");
               var showButton = $("<button class='showBtn btn btn-success badge'>More info</button>");
               newLi.attr("data-id", bookNamesArray[i].id);
               newLi.text(bookNamesArray[i].name);
               newLi.append(showButton);
               newLi.append(removeButton);
               booksList.append(newLi);
           }
       }).fail(function(xhr,status,error) {
           console.log("Load all books ajax failed");
       });
   }; 
   
    loadAllBooks();
    
    var booksList = $("#listWithBooks");
    booksList.on("click", ".showBtn", function(event) {
        var button = $(this);
        var buttonParent = button.parent();
        var bookId = buttonParent.data("id");
        
        $.ajax({
            url: "http://192.168.33.22/REST_bookshelf/api/books.php",
            method: "GET",
            data: {id: bookId},
            dataType: "JSON"
        }).done(function(book) {
            var newDiv = $("<div id='bookInf'>" +
                                "<h2>" + book.title + "</h2>" +
                                "<p>" + book.author + "</p>" +
                                "<p>" + book.description + "</p>" +
                            "</div>");
            
            //Form for editing book info
            var editForm = $("<form id='editBook'>");
            var formHeader = $("<h3>Edit book info</h3>");
            var titleInput = $("<div class='form-group'><label>Title:<input class='form-control' id='name' name='title' type='text'></label></div>");
            var authorInput = $("<div class='form-group'><label>Author:<input class='form-control' id='author_name' name='author' type='text'></label></div>");;
            var descriptionInput = $("<div class='form-group'><label>Description:<textarea class='form-control' id='description' name='description'></textarea></label></div>");
            var submitBtn = $("<input name='editBtn' class='btn btn-success' id='editBook' type='submit' value='Edit'><br>");
            
            
            editForm.append(formHeader);
            editForm.append(titleInput);
            editForm.append(authorInput);
            editForm.append(descriptionInput);
            editForm.append(submitBtn);
            newDiv.append(editForm);
            
            buttonParent.append(newDiv);
            button.removeClass("showBtn");
            button.text("Hide info");
            button.addClass("hideBtn");
        }).fail(function(xhr,status,error) {
            console.log("AJAX failed when reading book with id " + bookId);
        });
        
        booksList.one("submit", "#editBook", function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            var editFormInfo = $(this).serialize();

            $.ajax({
                url: "http://192.168.33.22/REST_bookshelf/api/books.php",
                method: "PUT",
                data: "id=" + bookId + "&" + editFormInfo,
                dataType: "JSON"
            }).done(function() {
                loadAllBooks();
                console.log("Edited book info");
            }).fail(function(xhr,status,error) {
                console.log("AJAX failed when editing a book" + error);
            });
        });   
    });
    
    
    //Hiding a div with book info
    booksList.on("click", ".hideBtn", function(event) {
        var button = $(this);
        $("#bookInf").remove();
        button.removeClass("hideBtn");
        button.text("More info");
        button.addClass("showBtn");
    });
    
    //Removing a book
    booksList.on("click", ".delBtn", function(event) {
        var button = $(this);
        var buttonParent = button.parent();
        var bookId = buttonParent.data("id");
        var delBookId = "id=" + bookId;
        
       $.ajax({
            url: "http://192.168.33.22/REST_bookshelf/api/books.php",
            method: "DELETE",
            data: delBookId,
            dataType: "JSON"
        }).done(function() {
            loadAllBooks();
            console.log("Book deleted");
        }).fail(function(xhr,status,error) {
            console.log("AJAX failed when deleting a book");
        }); 
    });
    
    //Adding a new book
    var addBookForm = $("#addNewBook");
    addBookForm.on("submit", function(event) {
        event.preventDefault();
        var formInfo = addBookForm.serialize();
        
        $.ajax({
            url: "http://192.168.33.22/REST_bookshelf/api/books.php",
            method: "POST",
            data: formInfo,
            dataType: "JSON"
        }).done(function() {
            loadAllBooks();
            console.log("Added a new book");
        }).fail(function(xhr,status,error) {
            console.log("AJAX failed when adding a new book");
        });
    });
});