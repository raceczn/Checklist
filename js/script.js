$(document).ready(function() {
  document.querySelectorAll('.pagination .page-link').forEach(link => {
      link.addEventListener('click', function(event) {
          event.preventDefault();
          const url = this.getAttribute('href');
          window.location.href = url;
      });
  });

  // Function to load data
  function load_data(page) {
      $.ajax({
          url: 'pagination.php',
          method: 'GET',
          data: {
              page: page
          },
          success: function(data) {
              $('#student_data tbody').html(data);
          }
      });
  }
  load_data();

  // Search functionality
  $('#search').keyup(function() {
      var query = $(this).val();
      $.ajax({
          url: 'search.php',
          method: 'POST',
          data: {
              query: query
          },
          success: function(data) {
              $('#student_data tbody').html(data);
          }
      });
  });

  // Pagination click event
  $(document).on('click', '.pagination a', function(event) {
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      load_data(page);
  });

  // Refresh button click event
  $('#refreshButton').click(function() {
      window.location.href = 'http://localhost/studentchecklist/checklist_record.php';
  });
});