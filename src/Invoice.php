<?php
/**
 * Invoice class
 *
 * @author      Stanley Sie <swookon@gmail.com>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkSaccount;

class Invoice
{
    /**
     * database
     *
     * @var object
     */
    private $database;

    /**
     * initialize
     */
    public function __construct($db = null)
    {
        $this->database = $db;
    }

    /**
     * Get invoice information.
     *
     * @param int $payerId The payer ID.
     * @return array Invoice information array.
     */
    public function getInvoice($payerId) {
        $sql = <<<EOF
            SELECT * FROM user_extra_payer_invoice WHERE payer_id = :payerId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':payerId' => $payerId]);    
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Add invoice information.
     *
     * @param array $invoiceInfo The invoice information array.
     * @return bool Operation result. Returns true on success, false on failure.
     */
    public function addInvoice($invoiceInfo) {
        $sql = <<<EOF
            INSERT INTO user_extra_payer_invoice (payer_id, type, title, uniform_no, carrier_barcode)
            VALUES (:payer_id, :type, :title, :uniform_no, :carrier_barcode)
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':payer_id' => $invoiceInfo['payer_id'],
            ':type' => $invoiceInfo['type'],
            ':title' => $invoiceInfo['title'],
            ':uniform_no' => $invoiceInfo['uniform_no'],
            ':carrier_barcode' => $invoiceInfo['carrier_barcode']
        ]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Update invoice information.
     *
     * @param array $invoiceInfo The invoice information array.
     * @return bool Operation result. Returns true on success, false on failure.
     */
    public function updateInvoice($invoiceInfo) {
        $sql = <<<EOF
            UPDATE user_extra_payer_invoice 
            SET type = :type, title = :title, uniform_no = :uniform_no, carrier_barcode = :carrier_barcode
            WHERE payer_id = :payer_id
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':type' => $invoiceInfo['type'],
            ':title' => $invoiceInfo['title'],
            ':uniform_no' => $invoiceInfo['uniform_no'],
            ':carrier_barcode' => $invoiceInfo['carrier_barcode'],
            ':payer_id' => $invoiceInfo['payer_id']
        ]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    }

    /**
     * Delete invoice information.
     *
     * @param int $payerId The payer ID.
     * @return bool Operation result. Returns true on success, false on failure.
     */
    public function deleteInvoice($payerId) {
        $sql = <<<EOF
            DELETE FROM user_extra_payer_invoice WHERE payer_id = :payerId
EOF;
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':payerId' => $payerId]);
        if ($stmt->rowCount > 0) {
            return true;
        }
        return false;
    } 

}
