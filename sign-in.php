<!DOCTYPE html>
<html lang="en">

  <head>
    <?php include("supports/header.php")?>
    
  </head>

<body>
    <?php include("supports/nav.php")?>

  <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 align-self-center">
              <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                <div class="row">
                  <div class="col-lg-12">
                    <h1>Best Marketeers Loans</h1>
                    <p> We provide frictionless loans that helps marketeers grow their businesses</p>
                  </div>
                  <div class="col-lg-12">
                    <div class="white-button first-button scroll-to-section">
                      <a href="client-login" class="btn btn-outline-light">Apply Now <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                <h2 class="mb-3">Login Here</h2>
                <form id="loginForm" class="login" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Username</span>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="input-group-text" id="showpass_text" onclick="passReveal()"><i class="bi bi-eye"></i></span>
                    </div>
                    <div class="white-button first-button scroll-to-section">
                        <button class="btn btn-secondary w-100" id="loginBtn" type="submit">Login</button>
                    </div>
                    <?php echo base64_decode('RW1tYW51ZWwyMDE1'); ?>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Scripts -->
    <?php include("supports/footer.php")?>
    <script>
        var showpass = document.getElementById('showpass_text');
        var password = document.getElementById('password');
        function passReveal(){
            var password = document.getElementById('password');
            if(password.type == 'password') {
                password.type = 'text';
                showpass.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }else {
                password.type = 'password';
                showpass.innerHTML = '<i class="bi bi-eye"></i>';
            }
        }

        function loginsuccessNow(msg){
            toastr.warning(msg);
            toastr.options.progressBar = false;
            toastr.options.positionClass = "toast-top-right";
            toastr.options.showDuration = 3000;
        }
        function errorNow(msg){
            toastr.error(msg);
            toastr.options.progressBar = true;
            toastr.options.positionClass = "toast-top-center";
            toastr.options.showDuration = 1000;
        }
        var sign_in = document.getElementById('loginBtn');
        var LoginFormNow = document.getElementById('loginForm');
        var username = document.getElementById('username');
        var password = document.getElementById('password');
        
        var url = 'parsers/loginPage';
        var xhr = new XMLHttpRequest();
        
        LoginFormNow.addEventListener('submit', (event) => {
            event.preventDefault();
            if(username.value == ""){
                alert("phone number is required");
                // phone.focus();
                return false;
            }
            if(password.value == ""){
                alert("password is required");
                password.focus();
                return false;
            }
            
            var data = new FormData(LoginFormNow);
            xhr.open('POST', url, true);
            xhr.onreadystatechange = function(){
                if(xhr.readyState == 4 && xhr.status == 200) {
                    r = xhr.responseText;
                    if(r == 'Incorrect login credentials'){
                        loginsuccessNow("Sorry, Incorrect login credentials");
                        sign_in.innerHTML = 'Sign In';
                    }else{
                        loginsuccessNow(r);
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                    
                }else{

                }
            }
            sign_in.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
            xhr.send(data);
        })
    </script>
  </body>
</html>