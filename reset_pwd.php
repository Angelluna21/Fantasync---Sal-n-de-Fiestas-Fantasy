<?php
$u = App\Models\User::where('email', 'admin@fantasync.com')->first();
if ($u) {
    $u->password = bcrypt('password123');
    $u->save();
    echo "Password reset successful.\n";
} else {
    echo "User not found.\n";
}
