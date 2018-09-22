<?php
namespace App\Library\AdminFunction;

class Memcache{
    const CACHE_ON = 1 ;// 0: khong dung qua cache, 1: dung qua cache

    const CACHE_BANNER_ID = 'cache_banner_id_';


    const CACHE_INFO_MEMBER_ID = 'cache_info_member_id_';
    const CACHE_ALL_MEMBER = 'cache_all_member';

    const CACHE_ALEGO_PHONE_CARD_ID = 'cache_alego_phone_card_id_';

    const CACHE_BANK_INFORMATION_BORROWERS_ID = 'cache_bank_information_borrowers_id_';

    const CACHE_BILL_PAYMENT_ID = 'cache_bill_payment_id_';

    const CACHE_CALL_LOGS_ID = 'cache_call_logs_id_';

    const CACHE_CAREERS_ID = 'cache_careers_id_';

    const CACHE_COMMISSIONS_ID = 'cache_commissions_id_';

    const CACHE_CHECKOUT_LENDERS_ID = 'cache_checkout_lenders_id_';

    const CACHE_CHECK_LENDING_DUPLICATE_ID = 'cache_check_lending_duplicate_id_';

    const CACHE_CONTACTS_ID = 'cache_contacts_id_';

    const CACHE_PRODUCT_ID = 'cache_product_id_';

    const CACHE_CONTRACTS_ID = 'cache_contracts_id_';

    const CACHE_EXPERTISE_ID = 'cache_expertise_id_';

    const CACHE_FACEBOOK_ID = 'cache_facebook_id_';

    const CACHE_HISTORY_ID = 'cache_history_id_';

    const CACHE_IMAGES_ID = 'cache_images_id_';

    const CACHE_LITERACY_ID = 'cache_literacy_id_';

    const CACHE_LOANERS_ID = 'cache_loaners_id_';

    const CACHE_LOANS_ID = 'cache_loans_id_';

    const CACHE_MATRIX_ID = 'cache_matrix_id_';

    const CACHE_MESSAGES_ID = 'cache_messages_id_';

    const CACHE_PROMOTION_ID = 'cache_promotion_id_';

    const CACHE_PURPOSES_ID = 'cache_purposes_id_';

    const CACHE_RECEIPTS_ID = 'cache_receipts_id_';

    const CACHE_RELATIONSHIPS_ID = 'cache_relationships_id_';

    const CACHE_REPAYMENTS_ID = 'cache_repayments_id_';

    const CACHE_SESSION_ID = 'cache_session_id_';

    const CACHE_TOKENS_ID = 'cache_tokens_id_';

    const CACHE_USERS_NOTI_ID = 'cache_users_noti_id_';

    const CACHE_USERS_PHONE_STRINGEE_CALL_ID = 'cache_users_phone_stringee_call_id_';

    const CACHE_USERS_PHONE_STRINGEE_AGENT_ID = 'cache_users_phone_stringee_agent_id_';

    const CACHE_USERS_PERMISSION_STRINGEE_CALL_ID = 'cache_users_permission_stringee_call_id_';

    const CACHE_USERS_LOGS_STRINGEE_CALL_ID = 'cache_users_logs_stringee_call_id_';

    const CACHE_USERS_LOAN_LOGS_ID = 'cache_users_loan_logs_id_';

    const CACHE_USERS_LOAN_ID = 'cache_users_loan_id_';

    const CACHE_TRANSACTION_LOANER_ID = 'cache_transaction_loaner_id_';

    const CACHE_SMS_FORGOT_LOG_ID = 'cache_sms_forgot_log_id_';

    const CACHE_REPAYMENT_METHOD_COMMISSTION_ID = 'cache_repayment_method_commisstion_id_';

    const CACHE_REPAYMENT_COMMISSION_ID = 'cache_repayment_commission_id_';

    const CACHE_REMINDER_DEPT_ID = 'cache_reminder_dept_id_';

    const CACHE_REMINDER_BORROWER_ID = 'cache_reminder_borrower_id_';

    const CACHE_RECEIPT_COMMISSION_ID = 'cache_receipt_commission_id_';

    const CACHE_PUSH_NOTIFICATION_ID = 'cache_push_notification_id_';

    const CACHE_PRODUCT_DOCUMENT_TYPE_ID = 'cache_product_document_type_id_';

    const CACHE_PREQUALIFICATI_ID = 'cache_prequalificati_id_';

    const CACHE_POPUP_LENDER_ID = 'cache_popup_lender_id_';

    const CACHE_POINTS_CHARGE_ID = 'cache_points_charge_id_';

    const CACHE_POINTS_CHARGE_HISTORY_ID = 'cache_points_charge_history_id_';

    const CACHE_PHONE_COMPANY_FINANCE_ID = 'cache_phone_company_finance_id_';

    const CACHE_PAYMENT_METHODS_ID = 'cache_payment_methods_id_';

    const CACHE_OPTION_POINTS_ID = 'cache_option_points_id_';

    const CACHE_OPTION_COMMISSION_ID = 'cache_option_commission_id_';

    const CACHE_NUMBER_CONTRACT_LENDING_ID = 'cache_number_contract_lending_id_';

    const CACHE_NOTIFICATIONS_ID = 'cache_notifications_id_';

    const CACHE_MESSAGE_SMS_ID = 'cache_message_sms_id_';

    const CACHE_MARKETING_ID = 'cache_marketing_id_';

    const CACHE_LOCATIONS_ID = 'cache_locations_id_';

    const CACHE_LOANS_FREE_ID = 'cache_loans_free_id_';

    const CACHE_LOANER_BACKLISTS_ID = 'cache_loaner_backlists_id_';

    const CACHE_LOAN_DOCUMENT_ID = 'cache_loan_document_id_';

    const CACHE_LENDER_TOKEN_ID = 'cache_lender_token_id_';

    const CACHE_LENDERS_ID = 'cache_lenders_id_';

    const CACHE_LENDER_NOTIFICATIONS_ID = 'cache_lender_notifications_id_';

    const CACHE_LENDER_LOANS_ID = 'cache_lender_loans_id_';

    const CACHE_LENDER_HISTORY_ID = 'cache_lender_history_id_';

    const CACHE_LENDER_DISBURSE_SLIPS_ID = 'cache_lender_disburse_slips_id_';

    const CACHE_LENDER_CONTRACTS_ID = 'cache_lender_contracts_id_';

    const CACHE_LENDER_CAREERS_ID = 'cache_lender_careers_id_';

    const CACHE_LENDER_APPORTIONS_ID = 'cache_lender_apportions_id_';

    const CACHE_HISTORY_OPTION_POINTS_ID = 'cache_history_option_points_id_';

    const CACHE_GIFT_CHARGE_ID = 'cache_gift_charge_id_';

    const CACHE_FRIENDS_FB360_ID = 'cache_friends_fb360_id_';

    const CACHE_FACEBOOK_FRIENDS_ID = 'cache_facebook_friends_id_';

    const CACHE_DOCUMENT_TYPE_ID = 'cache_document_type_id_';

    const CACHE_DOCUMENT_ENTITY_ATTRIBUTE_VALUE_ID = 'cache_document_entity_attribute_value_id_';

    const CACHE_DOCUMENT_ENTITY_ATTRIBUTE_ID = 'cache_document_entity_attribute_id_';

    const CACHE_DOCUMENT_ENTITY_ID = 'cache_document_entity_id_';

    const CACHE_DEVICE_APP_ID = 'cache_device_app_id_';

    const CACHE_CONTRACT_INFO_DISBURSED_AUTO_ID = 'cache_contract_info_disbursed_auto_id_';

    const CACHE_CONTRACT_DOCUMENT_ENTITY_ATTRIBUTE_VALUE_ID = 'cache_contract_document_entity_attribute_value_id_';

    const CACHE_CONTRACT_DOCUMENT_ENTITY_ID = 'cache_contract_document_entity_id_';



    const CACHE_DEPARTMENT_ID = 'cache_department_id_';
    

}
