<?php
/**
 * Credit card class
 *
 * @author      Stanley Sie <swookon@gmail.com>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkSaccount;

class CreditCard 
{
    // Database connection
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * Get credit cards of a user.
     *
     * @param int $userId The user ID.
     * @return array The credit card information array.
     */
    public function getCreditCards($userId)
    {
        $sql = <<<EOF
            SELECT id, user_id, type, card_last_four, expire_date, default_card FROM user_extra_creditcard WHERE user_id = :userId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Get credit card with a trade ID.
     *
     * @param int $tradeId The trade ID.
     * @return array The credit card information array.
     */
    public function getCardWithTradeID($tradeId)
    {
        $sql = <<<EOF
            SELECT id, user_id, type, card_last_four, expire_date, default_card FROM user_extra_creditcard WHERE rec_trade_id = :tradeId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':tradeId' => $tradeId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Add credit card.
     *
     * @param array $cardInfo The credit card information array.
     * @return bool Whether the operation is successful.
     */
    public function addCreditCard($cardInfo)
    {
        $sql = <<<EOF
            INSERT INTO user_extra_creditcard (user_id, rec_trade_id, type, card_key, card_token, card_first_six, card_last_four, expire_date, default_card) 
            VALUES (:user_id, :rec_trade_id, :type, :card_key, :card_token, :card_first_six, :card_last_four, :expire_date, :default_card)
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':user_id' => $cardInfo['user_id'],
            ':rec_trade_id' => $cardInfo['rec_trade_id'],
            ':type' => $cardInfo['type'],
            ':card_key' => $cardInfo['card_key'],
            ':card_token' => $cardInfo['card_token'],
            ':card_first_six' => $cardInfo['card_first_six'],
            ':card_last_four' => $cardInfo['card_last_four'],
            ':expire_date' => $cardInfo['expire_date'],
            ':default_card' => $cardInfo['default_card']
        ]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Set default credit card.
     *
     * @param int $cardId The credit card ID.
     * @return bool Whether the operation is successful.
     */
    public function setDefaultCreditCard($cardId)
    {
        $sql = <<<EOF
            UPDATE user_extra_creditcard SET default_card = 1 WHERE id = :cardId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':cardId' => $cardId]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Delete credit card.
     *
     * @param int $cardId The credit card ID.
     * @return bool Whether the operation is successful.
     */
    public function deleteCreditCard($cardId)
    {
        $sql = <<<EOF
            DELETE FROM user_extra_creditcard WHERE id = :cardId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':cardId' => $cardId]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }
}
