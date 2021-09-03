<!-- left navigation panel loaded by php code -->
<div id="left_nav">
    <ul>
        <li><a href="#" class="button" onclick="movieShowAllClick();">Show all movies</a></li>
        <li><a href="#" class="button" onclick="movieNewReleaseClick();">New releases</a></li>
        <li><a href="#" class="button" onclick="movieFilterClick();">Filter Movies</a></li>
        <li><a href="#" class="button" onclick="movieActorFilterClick();">Search by Actor</a></li>
        <li><a href="#" class="button" onclick="movieDirectorFilterClick();">Search by Director</a></li>
        <li><a href="#" class="button" onclick="movieGenreFilterClick();">Search by Genre</a></li>
        <li><a href="#" class="button" onclick="movieClassificationFilterClick();">Search by Classification</a></li>
    </ul>
    <h1>User Menu</h1>
    <ul>
        <li>
        <?php
        if(!empty($_SESSION['member_id']))
            echo '<a href="#" class="button" onclick="usercheckout_btnClicked();">Check Out</a>';
       else
            echo '<a href="#" class="button" onclick="memberLoginFormClick();">Log In</a>';
        ?>
        </li>
        <li><a href="#" class="button" onclick="euserlogout_btnClicked();">Logout</a></li>
    </ul>
    <ul>
        <li><a href="admin/moviezone_admin_login.php" class="button" style="margin-top: 50px;">Admin</a></li>
    </ul>
</div>