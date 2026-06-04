ALTER TABLE quote_requests
    ADD COLUMN products_json JSON NULL AFTER message;
