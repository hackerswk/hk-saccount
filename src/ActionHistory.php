<?php
/**
 * Action history class
 *
 * @author      Stanley Sie <swookon@gmail.com>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkSaccount;

class ActionHistory 
{
    // Database connection
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * Get account action history.
     *
     * @param int $userId The user ID.
     * @return array The account action history.
     */
    public function getActionHistory($userId)
    {
        $sql = <<<EOF
            SELECT * FROM user_action_history WHERE user_id = :userId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Add action history.
     *
     * @param int $userId The user ID.
     * @param string $action The action performed.
     * @return bool Whether the operation is successful.
     */
    public function addActionHistory($userId, $action)
    {
        $sql = <<<EOF
            INSERT INTO user_action_history (user_id, action) 
            VALUES (:userId, :action)
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':userId' => $userId,
            ':action' => $action
        ]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }
}