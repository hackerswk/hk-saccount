<?php
/**
 * Payer class
 *
 * @author      Stanley Sie <swookon@gmail.com>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkSaccount;

class Payer
{
    // Database connection
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * Get payer information.
     *
     * @param int $payerId The payer ID.
     * @return array|null The payer information array or null.
     */
    public function getPayer($payerId)
    {
        $sql = <<<EOF
            SELECT * FROM user_extra_payer WHERE id = :payerId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':payerId' => $payerId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Get list of payer information.
     *
     * @param int $userId The user ID.
     * @return array The list of payer information array.
     */
    public function getPayers($userId)
    {
        $sql = <<<EOF
            SELECT * FROM user_extra_payer WHERE user_id = :userId AND status = 1
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Add a new payer.
     *
     * @param array $payerInfo The payer information array.
     * @return bool The operation result. Returns true on success, false on failure.
     */
    public function addPayer($payerInfo)
    {
        $sql = <<<EOF
            INSERT INTO user_extra_payer (user_id, name, billing_address, mobile, phone_office, phone_home, set_default)
            VALUES (:user_id, :name, :billing_address, :mobile, :phone_office, :phone_home, :set_default)
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':user_id' => $payerInfo['user_id'],
            ':name' => $payerInfo['name'],
            ':billing_address' => $payerInfo['billing_address'],
            ':mobile' => $payerInfo['mobile'],
            ':phone_office' => $payerInfo['phone_office'],
            ':phone_home' => $payerInfo['phone_home'],
            ':set_default' => $payerInfo['set_default'],
        ]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Update payer information.
     *
     * @param array $payerInfo The payer information array.
     * @return bool The operation result. Returns true on success, false on failure.
     */
    public function updatePayer($payerInfo)
    {
        $sql = <<<EOF
            UPDATE user_extra_payer
            SET name = :name, billing_address = :billing_address, mobile = :mobile,
            phone_office = :phone_office, phone_home = :phone_home, set_default = :set_default
            WHERE id = :id
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':name' => $payerInfo['name'],
            ':billing_address' => $payerInfo['billing_address'],
            ':mobile' => $payerInfo['mobile'],
            ':phone_office' => $payerInfo['phone_office'],
            ':phone_home' => $payerInfo['phone_home'],
            ':set_default' => $payerInfo['set_default'],
            ':id' => $payerInfo['id'],
        ]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Set all payers' set_default to 0.
     *
     * @param int $userId The user ID.
     * @return bool The operation result. Returns true on success, false on failure.
     */
    public function initPayers($userId)
    {
        $sql = <<<EOF
            UPDATE user_extra_payer SET set_default = 0 WHERE user_id = :userId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Delete a payer.
     *
     * @param int $payerId The payer ID.
     * @return bool The operation result. Returns true on success, false on failure.
     */
    public function deletePayer($payerId)
    {
        $sql = <<<EOF
            DELETE FROM user_extra_payer WHERE id = :payerId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':payerId' => $payerId]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

}
