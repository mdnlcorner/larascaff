<?php

namespace Mulaidarinull\Larascaff\Concerns;

trait HasAuth
{
    use EmailVerification;
    use Login;
    use Registration;
    use ResetPassword;
}
