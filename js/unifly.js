// Navbar Menu
const hamburgerMenu = document.getElementById('hamburgerMenu');
const mobileMenu = document.getElementById('mobile-menu');
const menuClosed = document.getElementById('menuClosed');
const menuOpen = document.getElementById('menuOpen');
const navbarContainer = document.getElementById('navbarContainer');
const dashboardMenu = document.getElementById('dashboardMenu');
const aboutMeMenu = document.getElementById('aboutMeMenu');
const docsMenu = document.getElementById('docsMenu');
// const postmanMenu = document.getElementById('postmanMenu');

// Navbar Mobile Menu
const dashboardMobileMenu = document.getElementById('dashboardMobileMenu');
const aboutMeMobileMenu = document.getElementById('aboutMeMobileMenu');
const docsMobileMenu = document.getElementById('docsMobileMenu');
const postmanMobileMenu = document.getElementById('postmanMobileMenu');

// Sidebar Menu
const sidebar = document.getElementById('sidebar');
const airlines = document.getElementById('airlines');
const bookings = document.getElementById('bookings');
const flights = document.getElementById('flights');
const seats = document.getElementById('seats');
const users = document.getElementById('users');

// Sidebar Mobile Menu
const airlinesMobile = document.getElementById('airlinesMobile');
const bookingsMobile = document.getElementById('bookingsMobile');
const flightsMobile = document.getElementById('flightsMobile');
const seatsMobile = document.getElementById('seatsMobile');
const usersMobile = document.getElementById('usersMobile');

// Table Airlines
const airlinesTable = document.getElementById('airlinesTable');
const bookingsTable = document.getElementById('bookingsTable');
const flightsTable = document.getElementById('flightsTable');
const seatsTable = document.getElementById('seatsTable');
const usersTable = document.getElementById('usersTable');

// Isi Menu
const dashboard = document.getElementById('dashboard');
const aboutMe = document.getElementById('aboutMe');
const docs = document.getElementById('docs');
// const postman = document.getElementById('postman');

// Fitur
const loadingBackground = document.getElementById('loading-bg');
const loading = document.getElementById('loading');

// Loading
function showLoading() {
  loadingBackground.classList.add('active-loading-bg', 'z-50', 'flex');
  loadingBackground.classList.remove('hidden');
  loading.classList.add('active-loading');
  setTimeout(function () {
    loadingBackground.classList.add('hidden');
    loadingBackground.classList.remove('active-loading-bg', 'flex', 'z-50');
    loading.classList.remove('active-loading');
  }, 750);
}


// Mobile Menu
function toggleMobileMenu() {
  const expanded = hamburgerMenu.getAttribute('aria-expanded') === 'true' || false;
  hamburgerMenu.setAttribute('aria-expanded', !expanded);
  mobileMenu.hidden = !mobileMenu.hidden;
}

// Icon Mobile Menu
function toggleIconMenu() {
  menuClosed.classList.toggle('hidden');
  menuOpen.classList.toggle('hidden');
}

// Border Navbar Menu
function toggleBorderNavbar() {
  navbarContainer.classList.toggle('border-b');
}

// Menu Dashboard Selected
function dashboardMenuSelected() {
  dashboardMenu.classList.add('border-sky-500', 'text-sky-700');
  dashboardMenu.classList.remove('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
  dashboard.classList.remove('hidden');
}

// Menu aboutMe Selected
function aboutMeMenuSelected() {
  aboutMeMenu.classList.add('border-sky-500', 'text-sky-700');
  aboutMeMenu.classList.remove('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
  aboutMe.classList.remove('hidden');
}

// Menu Docs Selected
function docsMenuSelected() {
  docsMenu.classList.add('border-sky-500', 'text-sky-700');
  docsMenu.classList.remove('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
  docs.classList.remove('hidden');
}

// Menu Postman Selected
// function postmanMenuSelected() {
//   postmanMenu.classList.add('border-sky-500', 'text-sky-700');
//   postmanMenu.classList.remove('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
//   postman.classList.remove('hidden');
// }

// Menu Dashboard Not Selected 
function removedashboardMenuSelected() {
  dashboardMenu.classList.add('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
  dashboardMenu.classList.remove('border-sky-500', 'text-sky-700');
  dashboard.classList.add('hidden');
}

// Menu aboutMe Not Selected
function removeaboutMeMenuSelected() {
  aboutMeMenu.classList.add('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
  aboutMeMenu.classList.remove('border-sky-500', 'text-sky-700');
  aboutMe.classList.add('hidden');
}

// Menu Docs Not Selected
function removedocsMenuSelected() {
  docsMenu.classList.add('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
  docsMenu.classList.remove('border-sky-500', 'text-sky-700');
  docs.classList.add('hidden');
}

// Menu Postman Not Selected
// function removepostmanMenuSelected() {
//   postmanMenu.classList.add('hover:border-b-2', 'hover:border-slate-300', 'border-transparent');
//   postmanMenu.classList.remove('border-sky-500', 'text-sky-700');
//   postman.classList.add('hidden');
// }

// Menu Dashboard Clicked
function dashboardMenuClicked() {
  localStorage.setItem('selectedNavbarMenu', 'dashboardMenu');
  showLoading();
  dashboardMenuSelected();
  removeaboutMeMenuSelected();
  removedocsMenuSelected();
  // removepostmanMenuSelected();
}

// Menu aboutMe Clicked
function aboutMeMenuClicked() {
  localStorage.setItem('selectedNavbarMenu', 'aboutMeMenu');
  showLoading();
  aboutMeMenuSelected();
  removedashboardMenuSelected();
  removedocsMenuSelected();
  // removepostmanMenuSelected();
}

// Menu Docs Clicked
function docsMenuClicked() {
  localStorage.setItem('selectedNavbarMenu', 'docsMenu');
  showLoading();
  docsMenuSelected();
  removedashboardMenuSelected();
  removeaboutMeMenuSelected();
  // removepostmanMenuSelected();
}

// Menu Postman Clicked
// function postmanMenuClicked() {
//   localStorage.setItem('selectedNavbarMenu', 'postmanMenu');
//   showLoading();
//   postmanMenuSelected();
//   removedashboardMenuSelected();
//   removeaboutMeMenuSelected();
//   removedocsMenuSelected();
// }

// Menu Dashboard Mobile Selected
function dashboardMobileMenuSelected() {
  dashboard.classList.remove('hidden');
}

// Menu aboutMe Mobile Selected
function aboutMeMobileMenuSelected() {
  aboutMe.classList.remove('hidden');
}

// Menu Docs Mobile Selected
function docsMobileMenuSelected() {
  docs.classList.remove('hidden');
}

// Menu Postman Mobile Selected
// function postmanMobileMenuSelected() {
//   postman.classList.remove('hidden');
// }

// Menu Dashboard Mobile Not Selected 
function removedashboardMobileMenuSelected() {
  dashboard.classList.add('hidden');
}

// Menu aboutMe Mobile Not Selected
function removeaboutMeMobileMenuSelected() {
  aboutMe.classList.add('hidden');
}

// Menu Docs Mobile Not Selected
function removedocsMobileMenuSelected() {
  docs.classList.add('hidden');
}

// Menu Postman Mobile Not Selected
// function removepostmanMobileMenuSelected() {
//   postman.classList.add('hidden');
// }

// Menu Dashboard Mobile Clicked
function dashboardMobileMenuClicked() {
  localStorage.setItem('selectedNavbarMenu', 'dashboardMenu');
  localStorage.setItem('selectedMobileNavbarMenu', 'dashboardMobileMenu');
  showLoading();
  dashboardMenuSelected();
  removeaboutMeMenuSelected();
  removedocsMenuSelected();
  // removepostmanMenuSelected();
  dashboardMobileMenuSelected();
  removeaboutMeMobileMenuSelected();
  removedocsMobileMenuSelected();
  // removepostmanMobileMenuSelected();
}

// Menu aboutMe Mobile Clicked
function aboutMeMobileMenuClicked() {
  localStorage.setItem('selectedNavbarMenu', 'aboutMeMenu');
  localStorage.setItem('selectedMobileNavbarMenu', 'aboutMeMobileMenu');
  showLoading();
  aboutMeMenuSelected();
  removedashboardMenuSelected();
  removedocsMenuSelected();
  // removepostmanMenuSelected();
  aboutMeMobileMenuSelected();
  removedashboardMobileMenuSelected();
  removedocsMobileMenuSelected();
  // removepostmanMobileMenuSelected();
}

// Menu Docs Mobile Clicked
function docsMobileMenuClicked() {
  localStorage.setItem('selectedNavbarMenu', 'docsMenu');
  localStorage.setItem('selectedMobileNavbarMenu', 'docsMobileMenu');
  showLoading();
  docsMenuSelected();
  removedashboardMenuSelected();
  removeaboutMeMenuSelected();
  // removepostmanMenuSelected();
  docsMobileMenuSelected();
  removedashboardMobileMenuSelected();
  removeaboutMeMobileMenuSelected();
  // removepostmanMobileMenuSelected();
}

// Menu Postman Mobile Clicked
// function postmanMobileMenuClicked() {
//   localStorage.setItem('selectedNavbarMenu', 'postmanMenu');
//   localStorage.setItem('selectedMobileNavbarMenu', 'postmanMobileMenu');
//   showLoading();
//   postmanMenuSelected();
//   removedashboardMenuSelected();
//   removeaboutMeMenuSelected();
//   removedocsMenuSelected();
//   postmanMobileMenuSelected();
//   removedashboardMobileMenuSelected();
//   removeaboutMeMobileMenuSelected();
//   removedocsMobileMenuSelected();
// }

// Airlines Selected
function airlinesSelected() {
  airlines.classList.add('bg-sky-700');
  airlines.classList.remove('hover:bg-sky-700');
  airlinesTable.classList.add('block');
  airlinesTable.classList.remove('hidden');
}

// Bookings Selected
function bookingsSelected() {
  bookings.classList.add('bg-sky-700');
  bookings.classList.remove('hover:bg-sky-700');
  bookingsTable.classList.add('block');
  bookingsTable.classList.remove('hidden');
}

// Flights Selected
function flightsSelected() {
  flights.classList.add('bg-sky-700');
  flights.classList.remove('hover:bg-sky-700');
  flightsTable.classList.add('block');
  flightsTable.classList.remove('hidden');
}

// Seats Selected
function seatsSelected() {
  seats.classList.add('bg-sky-700');
  seats.classList.remove('hover:bg-sky-700');
  seatsTable.classList.add('block');
  seatsTable.classList.remove('hidden');
}

// Users Selected
function usersSelected() {
  users.classList.add('bg-sky-700');
  users.classList.remove('hover:bg-sky-700');
  usersTable.classList.add('block');
  usersTable.classList.remove('hidden');
}

// Airlines Not Selected
function removeAirlinesSelected() {
  airlines.classList.add('hover:bg-sky-700');
  airlines.classList.remove('bg-sky-700');
  airlinesTable.classList.add('hidden');
  airlinesTable.classList.remove('block');
}

// Bookings Not Selected
function removeBookingsSelected() {
  bookings.classList.add('hover:bg-sky-700');
  bookings.classList.remove('bg-sky-700');
  bookingsTable.classList.add('hidden');
  bookingsTable.classList.remove('block');
}

// Flights Not Selected
function removeFlightsSelected() {
  flights.classList.add('hover:bg-sky-700');
  flights.classList.remove('bg-sky-700');
  flightsTable.classList.add('hidden');
  flightsTable.classList.remove('block');
}

// Seats Not Selected
function removeSeatsSelected() {
  seats.classList.add('hover:bg-sky-700');
  seats.classList.remove('bg-sky-700');
  seatsTable.classList.add('hidden');
  seatsTable.classList.remove('block');
}

// Users Not Selected
function removeUsersSelected() {
  users.classList.add('hover:bg-sky-700');
  users.classList.remove('bg-sky-700');
  usersTable.classList.add('hidden');
  usersTable.classList.remove('block');
}

// Airlines Clicked
function airlinesClicked() {
  localStorage.setItem('selectedSidebarMenu', 'airlines');
  localStorage.setItem('selectedMobileSidebarMenu', 'airlines');
  airlinesSelected();
  removeBookingsSelected();
  removeFlightsSelected();
  removeSeatsSelected();
  removeUsersSelected();
  airlinesMobileSelected();
  removeBookingsMobileSelected();
  removeFlightsMobileSelected();
  removeSeatsMobileSelected();
  removeUsersMobileSelected();
}

// Bookings Clicked
function bookingsClicked() {
  localStorage.setItem('selectedSidebarMenu', 'bookings');
  localStorage.setItem('selectedMobileSidebarMenu', 'bookings');
  bookingsSelected();
  removeAirlinesSelected();
  removeFlightsSelected();
  removeSeatsSelected();
  removeUsersSelected();
  bookingsMobileSelected();
  removeAirlinesMobileSelected();
  removeFlightsMobileSelected();
  removeSeatsMobileSelected();
  removeUsersMobileSelected();
}

// Flights Clicked
function flightsClicked() {
  localStorage.setItem('selectedSidebarMenu', 'flights');
  localStorage.setItem('selectedMobileSidebarMenu', 'flights');
  flightsSelected();
  removeAirlinesSelected();
  removeBookingsSelected();
  removeSeatsSelected();
  removeUsersSelected();
  flightsMobileSelected();
  removeAirlinesMobileSelected();
  removeBookingsMobileSelected();
  removeSeatsMobileSelected();
  removeUsersMobileSelected();
}

// Seats Clicked
function seatsClicked() {
  localStorage.setItem('selectedSidebarMenu', 'seats');
  localStorage.setItem('selectedMobileSidebarMenu', 'seats');
  seatsSelected();
  removeAirlinesSelected();
  removeBookingsSelected();
  removeFlightsSelected();
  removeUsersSelected();
  seatsMobileSelected();
  removeAirlinesMobileSelected();
  removeBookingsMobileSelected();
  removeFlightsMobileSelected();
  removeUsersMobileSelected();
}

// Users Clicked
function usersClicked() {
  localStorage.setItem('selectedSidebarMenu', 'users');
  localStorage.setItem('selectedMobileSidebarMenu', 'users');
  usersSelected();
  removeAirlinesSelected();
  removeBookingsSelected();
  removeFlightsSelected();
  removeSeatsSelected();
  usersMobileSelected();
  removeAirlinesMobileSelected();
  removeBookingsMobileSelected();
  removeFlightsMobileSelected();
  removeSeatsMobileSelected();
}

// Airlines Mobile Selected
function airlinesMobileSelected() {
  airlinesMobile.classList.add('bg-sky-700');
  airlinesMobile.classList.remove('hover:bg-sky-700');
  airlinesTable.classList.add('block');
  airlinesTable.classList.remove('hidden');
}

// Bookings Mobile Selected
function bookingsMobileSelected() {
  bookingsMobile.classList.add('bg-sky-700');
  bookingsMobile.classList.remove('hover:bg-sky-700');
  bookingsTable.classList.add('block');
  bookingsTable.classList.remove('hidden');
}

// Flights Mobile Selected
function flightsMobileSelected() {
  flightsMobile.classList.add('bg-sky-700');
  flightsMobile.classList.remove('hover:bg-sky-700');
  flightsTable.classList.add('block');
  flightsTable.classList.remove('hidden');
}

// Seats Mobile Selected
function seatsMobileSelected() {
  seatsMobile.classList.add('bg-sky-700');
  seatsMobile.classList.remove('hover:bg-sky-700');
  seatsTable.classList.add('block');
  seatsTable.classList.remove('hidden');
}

// Users Mobile Selected
function usersMobileSelected() {
  usersMobile.classList.add('bg-sky-700');
  usersMobile.classList.remove('hover:bg-sky-700');
  usersTable.classList.add('block');
  usersTable.classList.remove('hidden');
}

// Airlines Mobile Not Selected
function removeAirlinesMobileSelected() {
  airlinesMobile.classList.add('hover:bg-sky-700');
  airlinesMobile.classList.remove('bg-sky-700');
  airlinesTable.classList.add('hidden');
  airlinesTable.classList.remove('block');
}

// Bookings Mobile Not Selected
function removeBookingsMobileSelected() {
  bookingsMobile.classList.add('hover:bg-sky-700');
  bookingsMobile.classList.remove('bg-sky-700');
  bookingsTable.classList.add('hidden');
  bookingsTable.classList.remove('block');
}

// Flights Mobile Not Selected
function removeFlightsMobileSelected() {
  flightsMobile.classList.add('hover:bg-sky-700');
  flightsMobile.classList.remove('bg-sky-700');
  flightsTable.classList.add('hidden');
  flightsTable.classList.remove('block');
}

// Seats Mobile Not Selected
function removeSeatsMobileSelected() {
  seatsMobile.classList.add('hover:bg-sky-700');
  seatsMobile.classList.remove('bg-sky-700');
  seatsTable.classList.add('hidden');
  seatsTable.classList.remove('block');
}

// Users Mobile Not Selected
function removeUsersMobileSelected() {
  usersMobile.classList.add('hover:bg-sky-700');
  usersMobile.classList.remove('bg-sky-700');
  usersTable.classList.add('hidden');
  usersTable.classList.remove('block');
}

// Airlines Mobile Clicked
function airlinesMobileClicked() {
  localStorage.setItem('selectedSidebarMenu', 'airlines');
  localStorage.setItem('selectedMobileSidebarMenu', 'airlines');
  airlinesMobileSelected();
  removeBookingsMobileSelected();
  removeFlightsMobileSelected();
  removeSeatsMobileSelected();
  removeUsersMobileSelected();
  airlinesSelected();
  removeBookingsSelected();
  removeFlightsSelected();
  removeSeatsSelected();
  removeUsersSelected();

}

// Bookings Mobile Clicked
function bookingsMobileClicked() {
  localStorage.setItem('selectedSidebarMenu', 'bookings');
  localStorage.setItem('selectedMobileSidebarMenu', 'bookings');
  bookingsMobileSelected();
  removeAirlinesMobileSelected();
  removeFlightsMobileSelected();
  removeSeatsMobileSelected();
  removeUsersMobileSelected();
  bookingsSelected();
  removeAirlinesSelected();
  removeFlightsSelected();
  removeSeatsSelected();
  removeUsersSelected();
}

// Flights Mobile Clicked
function flightsMobileClicked() {
  localStorage.setItem('selectedSidebarMenu', 'flights');
  localStorage.setItem('selectedMobileSidebarMenu', 'flights');
  flightsMobileSelected();
  removeAirlinesMobileSelected();
  removeBookingsMobileSelected();
  removeSeatsMobileSelected();
  removeUsersMobileSelected();
  flightsSelected();
  removeAirlinesSelected();
  removeBookingsSelected();
  removeSeatsSelected();
  removeUsersSelected();
}

// Seats Mobile Clicked
function seatsMobileClicked() {
  localStorage.setItem('selectedSidebarMenu', 'seats');
  localStorage.setItem('selectedMobileSidebarMenu', 'seats');
  seatsMobileSelected();
  removeAirlinesMobileSelected();
  removeBookingsMobileSelected();
  removeFlightsMobileSelected();
  removeUsersMobileSelected();
  seatsSelected();
  removeAirlinesSelected();
  removeBookingsSelected();
  removeFlightsSelected();
  removeUsersSelected();
}

// Users Mobile Clicked
function usersMobileClicked() {
  localStorage.setItem('selectedSidebarMenu', 'users');
  localStorage.setItem('selectedMobileSidebarMenu', 'users');
  usersMobileSelected();
  removeAirlinesMobileSelected();
  removeBookingsMobileSelected();
  removeFlightsMobileSelected();
  removeSeatsMobileSelected();
  usersSelected();
  removeAirlinesSelected();
  removeBookingsSelected();
  removeFlightsSelected();
  removeSeatsSelected();
}

// Navbar Menu Tetap Terpilih Saat Halaman Direfresh
document.addEventListener("DOMContentLoaded", function () {
  let selectedNavbarMenu = localStorage.getItem('selectedNavbarMenu');
  if (selectedNavbarMenu === 'dashboardMenu') {
    dashboardMenuClicked();
  } else if (selectedNavbarMenu === 'aboutMeMenu') {
    aboutMeMenuClicked();
  } else if (selectedNavbarMenu === 'docsMenu') {
    docsMenuClicked();
  } 
  // else if (selectedNavbarMenu === 'postmanMenu') {
  //   postmanMenuClicked();
  // } 
  else {
    localStorage.setItem('selectedNavbarMenu', 'dashboardMenu')
    dashboardMenuSelected();
  }
});

// Navbar Menu Tetap Terpilih Saat Halaman Direfresh
document.addEventListener("DOMContentLoaded", function () {
  let selectedSidebarMenu = localStorage.getItem('selectedSidebarMenu');
  let selectedMobileSidebarMenu = localStorage.getItem('selectedMobileSidebarMenu');
  if (selectedSidebarMenu === 'airlines' && selectedMobileSidebarMenu === 'airlines') {
    airlinesSelected();
    airlinesMobileSelected();
  } else if (selectedSidebarMenu === 'bookings' && selectedMobileSidebarMenu === 'bookings') {
    bookingsClicked();
    bookingsMobileClicked();
  } else if (selectedSidebarMenu === 'flights' && selectedMobileSidebarMenu === 'flights') {
    flightsClicked();
    flightsMobileClicked();
  } else if (selectedSidebarMenu === 'seats' && selectedMobileSidebarMenu === 'seats') {
    seatsClicked();
    seatsMobileClicked();
  } else if (selectedSidebarMenu === 'users' && selectedMobileSidebarMenu === 'users') {
    usersClicked();
    usersMobileClicked();
  } else {
    localStorage.setItem('selectedSidebarMenu', 'airlines');
    localStorage.setItem('selectedMobileSidebarMenu', 'airlines');
    airlinesSelected();
    airlinesMobileSelected();
  }
});


// Event Click Mobile Menu
hamburgerMenu.addEventListener('click', toggleMobileMenu,);
hamburgerMenu.addEventListener('click', toggleIconMenu);
hamburgerMenu.addEventListener('click', toggleBorderNavbar);

// Event Click Navbar Menu
dashboardMenu.addEventListener('click', dashboardMenuClicked);
aboutMeMenu.addEventListener('click', aboutMeMenuClicked);
docsMenu.addEventListener('click', docsMenuClicked);
// postmanMenu.addEventListener('click', postmanMenuClicked);

// Event Click Mobile Navbar Menu
dashboardMobileMenu.addEventListener('click', dashboardMobileMenuClicked);
aboutMeMobileMenu.addEventListener('click', aboutMeMobileMenuClicked);
docsMobileMenu.addEventListener('click', docsMobileMenuClicked);
// postmanMobileMenu.addEventListener('click', postmanMobileMenuClicked);

// Event Click Sidebar Menu
airlines.addEventListener('click', airlinesClicked);
bookings.addEventListener('click', bookingsClicked);
flights.addEventListener('click', flightsClicked);
seats.addEventListener('click', seatsClicked);
users.addEventListener('click', usersClicked);

// Event Click Mobile Sidebar Menu
airlinesMobile.addEventListener('click', airlinesMobileClicked);
bookingsMobile.addEventListener('click', bookingsMobileClicked);
flightsMobile.addEventListener('click', flightsMobileClicked);
seatsMobile.addEventListener('click', seatsMobileClicked);
usersMobile.addEventListener('click', usersMobileClicked);

if (level == 0) {
  // Airlines Data
  $.ajax({
    url: '../api/v1/airlines.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { airlines } = result.data;
      $('#airlinesTable').append(
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn w-52 bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Add New Airlines </a>' +
        '</div>' +
        '<table id="airlinesTableData" class="table"></table>'
      );
      $('#airlinesTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Airlines Name</th>' +
        '<th>Airlines Code</th>' +
        '<th>Total Seats</th>' +
        '<th colspan="2" align="center">Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="airlinesData" class="bg-sky-300 text-black text-center">'
      );
      $.each(airlines, function (i, data) {
        $('#airlinesData').append(
          '<tr>' +
          '<td>' + data.airline_id + '</td>' +
          '<td>' + data.airline_name + '</td>' +
          '<td>' + data.airline_code + '</td>' +
          '<td>' + data.total_seats + '</td>' +
          '<td>' + '<a href="edit.php?airline_id=' + data.airline_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
          '<td>' + '<a id="deleteAirlines' + data.airline_id + '"class="btn btn-sm btn-block bg-red-500 hover:bg-red-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Delete </a>' + ' </td>' +
          '</tr>'
        );
        function setupDeleteButton() {
          $('[id^="deleteAirlines"]').off('click').click(function () {
            var airlineID = $(this).attr('id').replace('deleteAirlines', '');
            $('#delete-data-bg').removeClass('hidden');
            $('#delete-data').removeClass('hidden');
            $('#delete-data').addClass('flex');
            $('#delete-data-yes').off('click').click(function () {
              $.ajax({
                url: '../api/v1/airlines.php?id=' + airlineID,
                method: 'DELETE',
                headers: {
                  "Content-Type": "application/json",
                  "Authorization": accessToken
                },
                success: function () {
                  $.ajax({
                    url: '../api/v1/airlines.php',
                    method: 'GET',
                    headers: {
                      "Content-Type": "application/json",
                      "Authorization": accessToken
                    },
                    success: function (result) {
                      $('#delete-data-bg').addClass('hidden');
                      $('#delete-data').addClass('hidden');
                      $('#delete-data').removeClass('flex');
                      var { airlines } = result.data;
                      $('#airlinesData').empty();
                      $.each(airlines, function (i, data) {
                        $('#airlinesData').append(
                          '<tr>' +
                          '<td>' + data.airline_id + '</td>' +
                          '<td>' + data.airline_name + '</td>' +
                          '<td>' + data.airline_code + '</td>' +
                          '<td>' + data.total_seats + '</td>' +
                          '<td>' + '<a href="edit.php?airline_id=' + data.airline_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
                          '<td>' + '<a id="deleteAirlines' + data.airline_id + '"class="btn btn-sm btn-block bg-red-500 hover:bg-red-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Delete </a>' + ' </td>' +
                          '</tr>'
                        );
                      });
                      setupDeleteButton();
                    },
                    error: function (result) {
                      var { messages } = result.responseJSON;
                      if (messages == 'Access token has expired') {
                        $('#airlinesForm').addClass('hidden');
                        $('#flightsForm').addClass('hidden');
                        $('#seatsForm').addClass('hidden');
                        $('#usersForm').addClass('hidden');
                        $('#session-expired-bg').removeClass('hidden');
                        $('#session-expired').removeClass('hidden');
                        $('#session-expired').addClass('flex');
                        $('#session-expired-login').click(function () {
                          $.ajax({
                            url: '../api/v1/sessions.php?id=' + sessionID,
                            type: 'DELETE',
                            headers: {
                              "Content-Type": "application/json",
                              "Authorization": accessToken
                            },
                            success: function () {
                              $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                  localStorage.clear();
                                  window.location.href = "login.php";
                                }
                              });
                            },
                            error: function (result) {
                              console.log(result)
                            }
                          });
                        });
                      } else {
                        setTimeout(function () {
                          $('#loading-form').addClass('hidden');
                          $('#loading-form').removeClass('z-50', 'flex');
                          $('#alertErrorAirlines').removeClass('hidden');
                          $('#errorMessageAirlines').empty();
                          $('#errorMessageAirlines').append(messages);
                        }, 1000)
                      }
                    }
                  });
                }
              });
            });

            $('#delete-data-no').off('click').click(function () {
              $('#delete-data-bg').addClass('hidden');
              $('#delete-data').addClass('hidden');
              $('#delete-data').removeClass('flex');
              setupDeleteButton();
            });
          });
        }
        setupDeleteButton();
      });
    },
    error: function (result) {
      var { messages } = result.responseJSON
      if (messages == 'Access token has expired') {
        $('#session-expired-bg').removeClass('hidden');
        $('#session-expired').removeClass('hidden');
        $('#session-expired').addClass('flex');
        $('#session-expired-login').click(function () {
          $.ajax({
            url: '../api/v1/sessions.php?id=' + sessionID,
            type: 'DELETE',
            headers: {
              "Content-Type": "application/json",
              "Authorization": accessToken
            },
            success: function () {
              $.ajax({
                url: 'sessionDestroy.php',
                method: 'POST',
                success: function () {
                  localStorage.clear();
                  window.location.href = "login.php";
                }
              });
            },
            error: function (result) {
              console.log(result)
            }
          });
        });
      } else {
        $('#session-expired-bg').addClass('hidden');
        $('#session-expired').addClass('hidden');
      }
    }
  });

  // Bookings Data
  $.ajax({
    url: '../api/v1/bookings.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { bookings } = result.data;
      $('#bookingsTable').append(
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn w-52 bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Buy Ticket </a>' +
        '</div>' +
        '<table id="bookingsTableData" class="table"></table>'
      );
      $('#bookingsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Fullname</th>' +
        '<th>Airlines Name</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Booking Date</th>' +
        '<th>Seat Number</th>' +
        '<th>Total Passenger</th>' +
        '<th>Total Price</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="bookingsData" class="bg-sky-300 text-black text-center">'
      );
      $.each(bookings, function (i, data) {
        $('#bookingsData').append(
          '<tr>' +
          '<td>' + data.booking_id + '</td>' +
          '<td>' + data.fullname + '</td>' +
          '<td>' + data.airline_name + '</td>' +
          '<td>' + data.origin + '</td>' +
          '<td>' + data.destination + '</td>' +
          '<td>' + data.departure_time + '</td>' +
          '<td>' + data.arrival_time + '</td>' +
          '<td>' + data.booking_date + '</td>' +
          '<td>' + data.seat_number + '</td>' +
          '<td>' + data.total_passenger + '</td>' +
          '<td>' + data.total_price + '</td>' +
          '<td>' + '<a id="deleteBookings' + data.booking_id + '"class="btn btn-sm btn-block bg-red-500 hover:bg-red-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Delete </a>' + ' </td>' +
          '</tr>'
        );
        function setupDeleteButton() {
          $('[id^="deleteBookings"]').off('click').click(function () {
            var bookingID = $(this).attr('id').replace('deleteBookings', '');
            var bookingIDs = bookingID.split(',').map(function (item) {
              return item.trim();
            });
            var url = '../api/v1/bookings.php?id=' + bookingIDs.join('&id=');
            if (bookingIDs.length > 1) {
              url = url.replace('id=', '&id=');
            }
            $('#delete-data-bg').removeClass('hidden');
            $('#delete-data').removeClass('hidden');
            $('#delete-data').addClass('flex');
            $('#delete-data-yes').off('click').click(function () {
              $.ajax({
                url: url,
                method: 'DELETE',
                headers: {
                  "Content-Type": "application/json",
                  "Authorization": accessToken
                },
                success: function () {
                  $.ajax({
                    url: '../api/v1/bookings.php',
                    method: 'GET',
                    headers: {
                      "Content-Type": "application/json",
                      "Authorization": accessToken
                    },
                    success: function (result) {
                      $('#delete-data-bg').addClass('hidden');
                      $('#delete-data').addClass('hidden');
                      $('#delete-data').removeClass('flex');
                      var { bookings } = result.data;
                      $('#bookingsData').empty();
                      $.each(bookings, function (i, data) {
                        $('#bookingsData').append(
                          '<tr>' +
                          '<td>' + data.booking_id + '</td>' +
                          '<td>' + data.fullname + '</td>' +
                          '<td>' + data.airline_name + '</td>' +
                          '<td>' + data.origin + '</td>' +
                          '<td>' + data.destination + '</td>' +
                          '<td>' + data.departure_time + '</td>' +
                          '<td>' + data.arrival_time + '</td>' +
                          '<td>' + data.booking_date + '</td>' +
                          '<td>' + data.seat_number + '</td>' +
                          '<td>' + data.total_passenger + '</td>' +
                          '<td>' + data.total_price + '</td>' +
                          '<td>' + '<a id="deleteBookings' + data.booking_id + '"class="btn btn-sm btn-block bg-red-500 hover:bg-red-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Delete </a>' + ' </td>' +
                          '</tr>'
                        );
                      });
                      setupDeleteButton();
                    },
                    error: function (result) {
                      var { messages } = result.responseJSON;
                      console.log(messages)
                      if (messages == 'Access token has expired') {
                        $('#airlinesTable').addClass('hidden');
                        $('#bookingsTable').addClass('hidden');
                        $('#flightsTable').addClass('hidden');
                        $('#seatsTable').addClass('hidden');
                        $('#usersTable').addClass('hidden');
                        $('#session-expired-bg').removeClass('hidden');
                        $('#session-expired').removeClass('hidden');
                        $('#session-expired').addClass('flex');
                        $('#session-expired-login').click(function () {
                          $.ajax({
                            url: '../api/v1/sessions.php?id=' + sessionID,
                            type: 'DELETE',
                            headers: {
                              "Content-Type": "application/json",
                              "Authorization": accessToken
                            },
                            success: function () {
                              $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                  localStorage.clear();
                                  window.location.href = "login.php";
                                }
                              });
                            },
                            error: function (result) {
                              console.log(result)
                            }
                          });
                        });
                      } else {
                        $('#delete-data-bg').addClass('hidden');
                        $('#delete-data').addClass('hidden');
                        $('#delete-data').removeClass('flex');
                        $('#bookingsTable').empty();
                        $('#bookingsTableData').empty();
                        var { messages } = result.responseJSON;
                        $('#bookingsTable').append(
                          '<div id="alertErrorBookings" class="alert alert-error mb-3">' +
                          '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">' +
                          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />' +
                          '</svg>' +
                          '<span id="errorMessageBookings"></span>' +
                          '</div>' +
                          '<div class="mb-3">' +
                          '<a href="insert.php" class="btn w-52 bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Buy Ticket </a>' +
                          '</div>' +
                          '<table id="bookingsTableData" class="table"></table>'
                        );
                        $('#bookingsTableData').append(
                          '<thead class="bg-sky-700 text-white text-center">' +
                          '<tr>' +
                          '<th>ID</th>' +
                          '<th>Fullname</th>' +
                          '<th>Airlines Name</th>' +
                          '<th>Origin</th>' +
                          '<th>Destination</th>' +
                          '<th>Departure Time</th>' +
                          '<th>Arrival Time</th>' +
                          '<th>Booking Date</th>' +
                          '<th>Seat Number</th>' +
                          '<th>Total Passenger</th>' +
                          '<th>Total Price</th>' +
                          '<th>Action</th>' +
                          '</tr>' +
                          '</thead>' +
                          '<tbody id="bookingsData" class="bg-sky-300 text-black text-center">'
                        );
                        $('#errorMessageBookings').append(messages)
                      }
                    }
                  });
                },
                error: function (result) {
                  console.log(result.responseJSON)
                }
              });
            });

            $('#delete-data-no').off('click').click(function () {
              $('#delete-data-bg').addClass('hidden');
              $('#delete-data').addClass('hidden');
              $('#delete-data').removeClass('flex');
              setupDeleteButton();
            });
          });
        }
        setupDeleteButton();
      });
    },
    error: function (result) {
      var { messages } = result.responseJSON;
      $('#bookingsTable').append(
        '<div id="alertErrorAirlines" class="alert alert-error mb-3">' +
        '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">' +
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />' +
        '</svg>' +
        '<span id="errorMessageBookings"></span>' +
        '</div>' +
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn w-52 bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Buy Ticket </a>' +
        '</div>' +
        '<table id="bookingsTableData" class="table"></table>'
      );
      $('#bookingsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Fullname</th>' +
        '<th>Airlines Name</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Booking Date</th>' +
        '<th>Seat Number</th>' +
        '<th>Total Passenger</th>' +
        '<th>Total Price</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="bookingsData" class="bg-sky-300 text-black text-center">'
      );
      $('#errorMessageBookings').append(messages)
    }
  });

  // Flights Data
  $.ajax({
    url: '../api/v1/flights.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { flights } = result.data;
      $('#flightsTable').append(
        '<table id="flightsTableData" class="table"></table>'
      );
      $('#flightsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Airlines Name</th>' +
        '<th>Airlines Code</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Price</th>' +
        '<th>Available Seats</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="flightsData" class="bg-sky-300 text-black text-center">'
      );
      $.each(flights, function (i, data) {
        $('#flightsData').append(
          '<tr>' +
          '<td>' + data.flight_id + '</td>' +
          '<td>' + data.airline_name + '</td>' +
          '<td>' + data.airline_code + '</td>' +
          '<td>' + data.origin + '</td>' +
          '<td>' + data.destination + '</td>' +
          '<td>' + data.departure_time + '</td>' +
          '<td>' + data.arrival_time + '</td>' +
          '<td>' + data.price + '</td>' +
          '<td>' + data.available_seats + '</td>' +
          '<td>' + '<a href="edit.php?flight_id=' + data.flight_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
          '</tr>'
        );
      });
    }
  });

  // Seats Data
  $.ajax({
    url: '../api/v1/seats.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { seats } = result.data;
      $('#seatsTable').append(
        '<table id="seatsTableData" class="table"></table>'
      );
      $('#seatsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Seat Number</th>' +
        '<th>Is Available</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="seatsData" class="bg-sky-300 text-black text-center">'
      );
      $.each(seats, function (i, data) {
        $('#seatsData').append(
          '<tr>' +
          '<td>' + data.seat_id + '</td>' +
          '<td>' + data.origin + '</td>' +
          '<td>' + data.destination + '</td>' +
          '<td>' + data.departure_time + '</td>' +
          '<td>' + data.arrival_time + '</td>' +
          '<td>' + data.seat_number + '</td>' +
          '<td>' + data.is_available + '</td>' +
          '<td>' + '<a href="edit.php?seat_id=' + data.seat_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
          '</tr>'
        );
      });
    }
  });

  // Users Data
  $.ajax({
    url: '../api/v1/users.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { users } = result.data;
      $('#usersTable').append(
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Create New Account </a>' +
        '</div>' +
        '<table id="usersTableData" class="table"></table>'
      );
      $('#usersTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Username</th>' +
        '<th>Password</th>' +
        '<th>Email</th>' +
        '<th>Fullname</th>' +
        '<th>Address</th>' +
        '<th>Telephone</th>' +
        '<th colspan="2" align="center">Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="usersData" class="bg-sky-300 text-black text-center">'
      );
      $.each(users, function (i, data) {
        $('#usersData').append(
          '<tr>' +
          '<td>' + data.user_id + '</td>' +
          '<td>' + data.username + '</td>' +
          '<td>' + data.password + '</td>' +
          '<td>' + data.email + '</td>' +
          '<td>' + data.fullname + '</td>' +
          '<td>' + data.address + '</td>' +
          '<td>' + data.telephone + '</td>' +
          '<td>' + '<a href="edit.php?user_id=' + data.user_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
          '<td>' + '<a id="deleteUsers' + data.user_id + '"class="btn btn-sm btn-block bg-red-500 hover:bg-red-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Delete </a>' + ' </td>' +
          '</tr>'
        );
        function setupDeleteButton() {
          $('[id^="deleteUsers"]').off('click').click(function () {
            var userID = $(this).attr('id').replace('deleteUsers', '');
            $('#delete-data-bg').removeClass('hidden');
            $('#delete-data').removeClass('hidden');
            $('#delete-data').addClass('flex');
            $('#delete-data-yes').off('click').click(function () {
              $.ajax({
                url: '../api/v1/users.php?id=' + userID,
                method: 'DELETE',
                headers: {
                  "Content-Type": "application/json",
                  "Authorization": accessToken
                },
                success: function () {
                  $.ajax({
                    url: '../api/v1/users.php',
                    method: 'GET',
                    headers: {
                      "Content-Type": "application/json",
                      "Authorization": accessToken
                    },
                    success: function (result) {
                      $('#delete-data-bg').addClass('hidden');
                      $('#delete-data').addClass('hidden');
                      $('#delete-data').removeClass('flex');
                      var { users } = result.data;
                      $('#usersData').empty();
                      $.each(users, function (i, data) {
                        $('#usersData').append(
                          '<tr>' +
                          '<td>' + data.user_id + '</td>' +
                          '<td>' + data.username + '</td>' +
                          '<td>' + data.password + '</td>' +
                          '<td>' + data.email + '</td>' +
                          '<td>' + data.fullname + '</td>' +
                          '<td>' + data.address + '</td>' +
                          '<td>' + data.telephone + '</td>' +
                          '<td>' + '<a href="edit.php?user_id=' + data.user_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
                          '<td>' + '<a id="deleteUsers' + data.user_id + '"class="btn btn-sm btn-block bg-red-500 hover:bg-red-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Delete </a>' + ' </td>' +
                          '</tr>'
                        );
                      });
                      setupDeleteButton();
                    },
                    error: function (result) {
                      var { messages } = result.responseJSON;
                      if (messages == 'Access token has expired') {
                        $('#airlinesForm').addClass('hidden');
                        $('#flightsForm').addClass('hidden');
                        $('#seatsForm').addClass('hidden');
                        $('#usersForm').addClass('hidden');
                        $('#session-expired-bg').removeClass('hidden');
                        $('#session-expired').removeClass('hidden');
                        $('#session-expired').addClass('flex');
                        $('#session-expired-login').click(function () {
                          $.ajax({
                            url: '../api/v1/sessions.php?id=' + sessionID,
                            type: 'DELETE',
                            headers: {
                              "Content-Type": "application/json",
                              "Authorization": accessToken
                            },
                            success: function () {
                              $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                  localStorage.clear();
                                  window.location.href = "login.php";
                                }
                              });
                            },
                            error: function (result) {
                              console.log(result)
                            }
                          });
                        });
                      } else {
                        $('#delete-data-bg').addClass('hidden');
                        $('#delete-data').addClass('hidden');
                        $('#delete-data').removeClass('flex');
                        $('#usersTable').empty();
                        $('#usersTableData').empty();
                        var { messages } = result.responseJSON;
                        $('#usersTable').append(
                          '<div id="alertErrorUsers" class="alert alert-error mb-3">' +
                          '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">' +
                          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />' +
                          '</svg>' +
                          '<span id="errorMessageUsers"></span>' +
                          '</div>' +
                          '<div class="mb-3">' +
                          '<a href="insert.php" class="btn bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Create New Account </a>' +
                          '</div>' +
                          '<table id="usersTableData" class="table"></table>'
                        );
                        $('#usersTableData').append(
                          '<thead class="bg-sky-700 text-white text-center">' +
                          '<tr>' +
                          '<th>ID</th>' +
                          '<th>Username</th>' +
                          '<th>Password</th>' +
                          '<th>Email</th>' +
                          '<th>Fullname</th>' +
                          '<th>Address</th>' +
                          '<th>Telephone</th>' +
                          '<th colspan="2" align="center">Action</th>' +
                          '</tr>' +
                          '</thead>' +
                          '<tbody id="usersData" class="bg-sky-300 text-black text-center">'
                        );
                        $('#errorMessageUsers').append(messages)
                      }
                    }
                  });
                }
              });
            });

            $('#delete-data-no').off('click').click(function () {
              $('#delete-data-bg').addClass('hidden');
              $('#delete-data').addClass('hidden');
              $('#delete-data').removeClass('flex');
              setupDeleteButton();
            });
          });
        }
        setupDeleteButton();
      });
    },
    error: function (result) {
      var { messages } = result.responseJSON;
      $('#usersTable').append(
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Create New Account </a>' +
        '</div>' +
        '<table id="usersTableData" class="table"></table>'
      );
      $('#usersTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Username</th>' +
        '<th>Password</th>' +
        '<th>Email</th>' +
        '<th>Fullname</th>' +
        '<th>Address</th>' +
        '<th>Telephone</th>' +
        '<th colspan="2" align="center">Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="usersData" class="bg-sky-300 text-black text-center">'
      );
      $('#errorMessageUsers').append(messages)
    }
  });

} else if (level == 1) {
  // Airlines Data
  $.ajax({
    url: '../api/v1/airlines.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { airlines } = result.data;
      $('#airlinesTable').append(
        '<table id="airlinesTableData" class="table"></table>'
      );
      $('#airlinesTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Airlines Name</th>' +
        '<th>Airlines Code</th>' +
        '<th>Total Seats</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="airlinesData" class="bg-sky-300 text-black text-center">'
      );
      $.each(airlines, function (i, data) {
        $('#airlinesData').append(
          '<tr>' +
          '<td>' + data.airline_id + '</td>' +
          '<td>' + data.airline_name + '</td>' +
          '<td>' + data.airline_code + '</td>' +
          '<td>' + data.total_seats + '</td>' +
          '</tr>'
        );
      });
    },
    error: function (result) {
      var { messages } = result.responseJSON
      if (messages == 'Access token has expired') {
        $('#session-expired-bg').removeClass('hidden');
        $('#session-expired').removeClass('hidden');
        $('#session-expired').addClass('flex');
        $('#session-expired-login').click(function () {
          $.ajax({
            url: '../api/v1/sessions.php?id=' + sessionID,
            type: 'DELETE',
            headers: {
              "Content-Type": "application/json",
              "Authorization": accessToken
            },
            success: function () {
              $.ajax({
                url: 'sessionDestroy.php',
                method: 'POST',
                success: function () {
                  localStorage.clear();
                  window.location.href = "login.php";
                }
              });
            },
            error: function (result) {
              console.log(result)
            }
          });
        });
      } else {
        $('#session-expired-bg').addClass('hidden');
        $('#session-expired').addClass('hidden');
      }
    }
  });

  // Bookings Data
  $.ajax({
    url: '../api/v1/bookings.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { bookings } = result.data;
      $('#bookingsTable').append(
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn w-52 bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Buy Ticket </a>' +
        '</div>' +
        '<table id="bookingsTableData" class="table"></table>'
      );
      $('#bookingsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Fullname</th>' +
        '<th>Airlines Name</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Booking Date</th>' +
        '<th>Seat Number</th>' +
        '<th>Total Passenger</th>' +
        '<th>Total Price</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="bookingsData" class="bg-sky-300 text-black text-center">'
      );
      $.each(bookings, function (i, data) {
        $('#bookingsData').append(
          '<tr>' +
          '<td>' + data.booking_id + '</td>' +
          '<td>' + data.fullname + '</td>' +
          '<td>' + data.airline_name + '</td>' +
          '<td>' + data.origin + '</td>' +
          '<td>' + data.destination + '</td>' +
          '<td>' + data.departure_time + '</td>' +
          '<td>' + data.arrival_time + '</td>' +
          '<td>' + data.booking_date + '</td>' +
          '<td>' + data.seat_number + '</td>' +
          '<td>' + data.total_passenger + '</td>' +
          '<td>' + data.total_price + '</td>' +
          '</tr>'
        );
      });
    },
    error: function (result) {
      var { messages } = result.responseJSON;
      $('#bookingsTable').append(
        '<div id="alertErrorAirlines" class="alert alert-error mb-3">' +
        '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">' +
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />' +
        '</svg>' +
        '<span id="errorMessageBookings"></span>' +
        '</div>' +
        '<div class="mb-3">' +
        '<a href="insert.php" class="btn w-52 bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-10" tabindex="-1"> Buy Ticket </a>' +
        '</div>' +
        '<table id="bookingsTableData" class="table"></table>'
      );
      $('#bookingsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Fullname</th>' +
        '<th>Airlines Name</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Booking Date</th>' +
        '<th>Seat Number</th>' +
        '<th>Total Passenger</th>' +
        '<th>Total Price</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="bookingsData" class="bg-sky-300 text-black text-center">'
      );
      $('#errorMessageBookings').append(messages)
    }
  });

  // Flights Data
  $.ajax({
    url: '../api/v1/flights.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { flights } = result.data;
      $('#flightsTable').append(
        '<table id="flightsTableData" class="table"></table>'
      );
      $('#flightsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Airlines Name</th>' +
        '<th>Airlines Code</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Price</th>' +
        '<th>Available Seats</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="flightsData" class="bg-sky-300 text-black text-center">'
      );
      $.each(flights, function (i, data) {
        $('#flightsData').append(
          '<tr>' +
          '<td>' + data.flight_id + '</td>' +
          '<td>' + data.airline_name + '</td>' +
          '<td>' + data.airline_code + '</td>' +
          '<td>' + data.origin + '</td>' +
          '<td>' + data.destination + '</td>' +
          '<td>' + data.departure_time + '</td>' +
          '<td>' + data.arrival_time + '</td>' +
          '<td>' + data.price + '</td>' +
          '<td>' + data.available_seats + '</td>' +
          '</tr>'
        );
      });
    }
  });

  // Seats Data
  $.ajax({
    url: '../api/v1/seats.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { seats } = result.data;
      $('#seatsTable').append(
        '<table id="seatsTableData" class="table"></table>'
      );
      $('#seatsTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Origin</th>' +
        '<th>Destination</th>' +
        '<th>Departure Time</th>' +
        '<th>Arrival Time</th>' +
        '<th>Seat Number</th>' +
        '<th>Is Available</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="seatsData" class="bg-sky-300 text-black text-center">'
      );
      $.each(seats, function (i, data) {
        $('#seatsData').append(
          '<tr>' +
          '<td>' + data.seat_id + '</td>' +
          '<td>' + data.origin + '</td>' +
          '<td>' + data.destination + '</td>' +
          '<td>' + data.departure_time + '</td>' +
          '<td>' + data.arrival_time + '</td>' +
          '<td>' + data.seat_number + '</td>' +
          '<td>' + data.is_available + '</td>' +
          '</tr>'
        );
      });
    }
  });

  // Users Data
  $.ajax({
    url: '../api/v1/users.php',
    method: 'GET',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      var { users } = result.data;
      $('#usersTable').append(
        '<table id="usersTableData" class="table"></table>'
      );
      $('#usersTableData').append(
        '<thead class="bg-sky-700 text-white text-center">' +
        '<tr>' +
        '<th>ID</th>' +
        '<th>Username</th>' +
        '<th>Password</th>' +
        '<th>Email</th>' +
        '<th>Fullname</th>' +
        '<th>Address</th>' +
        '<th>Telephone</th>' +
        '<th>Action</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="usersData" class="bg-sky-300 text-black text-center">'
      );
      $.each(users, function (i, data) {
        $('#usersData').append(
          '<tr>' +
          '<td>' + data.user_id + '</td>' +
          '<td>' + data.username + '</td>' +
          '<td>' + data.password + '</td>' +
          '<td>' + data.email + '</td>' +
          '<td>' + data.fullname + '</td>' +
          '<td>' + data.address + '</td>' +
          '<td>' + data.telephone + '</td>' +
          '<td>' + '<a href="edit.php?user_id=' + data.user_id + '"class="btn btn-sm btn-block bg-emerald-500 hover:bg-emerald-700 border-0 text-white normal-case pb-0.5 px-5" tabindex="-1"> Edit </a>' + '</td>' +
          '</tr>'
        );
      });
    }
  });
} else {
  $.ajax({
    url: '../api/v1/sessions.php?id=' + sessionID,
    type: 'DELETE',
    headers: {
      "Content-Type": "application/json",
      "Authorization": accessToken
    },
    success: function (result) {
      $.ajax({
        url: 'sessionDestroy.php',
        method: 'POST',
        success: function (result) {
          alert('You\'re Hacker!')
          localStorage.clear();
          window.location.href = "login.php";
        }
      });
    },
    error: function (result) {
      console.log(result)
    }
  });
}


