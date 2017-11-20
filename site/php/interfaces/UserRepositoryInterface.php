<!--
  This interface defines all the services that the
  user repoistory can supply
-->
<?php

    interface UserRepositoryInterface {
        public function authenticateUser(User $user);
    }
 ?>
