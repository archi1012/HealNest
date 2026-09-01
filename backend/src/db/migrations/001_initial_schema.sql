CREATE EXTENSION IF NOT EXISTS citext;
CREATE EXTENSION IF NOT EXISTS pgcrypto;

CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    email CITEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    age SMALLINT,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    parent_id UUID REFERENCES users(id) ON DELETE SET NULL,
    email_verified_at TIMESTAMPTZ,
    remember_token VARCHAR(100),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT users_age_range CHECK (age IS NULL OR age BETWEEN 1 AND 120),
    CONSTRAINT users_role_valid CHECK (role IN ('user', 'parent', 'counselor', 'admin'))
);

CREATE TABLE password_reset_tokens (
    email CITEXT PRIMARY KEY REFERENCES users(email) ON UPDATE CASCADE ON DELETE CASCADE,
    token_hash TEXT NOT NULL,
    expires_at TIMESTAMPTZ NOT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE resources (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title VARCHAR(120) NOT NULL,
    category VARCHAR(80) NOT NULL,
    icon VARCHAR(10) NOT NULL,
    description VARCHAR(500) NOT NULL,
    external_url VARCHAR(255),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE mood_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    mood SMALLINT NOT NULL,
    note VARCHAR(500),
    logged_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT mood_logs_mood_range CHECK (mood BETWEEN 1 AND 5)
);

CREATE TABLE mood_log_tags (
    mood_log_id UUID NOT NULL REFERENCES mood_logs(id) ON DELETE CASCADE,
    tag VARCHAR(100) NOT NULL,
    PRIMARY KEY (mood_log_id, tag)
);

CREATE TABLE assessments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(10) NOT NULL,
    score SMALLINT NOT NULL,
    risk_level VARCHAR(10) NOT NULL,
    taken_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT assessments_type_valid CHECK (type IN ('PHQ9', 'GAD7', 'GENERAL')),
    CONSTRAINT assessments_score_valid CHECK (score >= 0),
    CONSTRAINT assessments_risk_level_valid CHECK (risk_level IN ('minimal', 'mild', 'moderate', 'severe'))
);

CREATE TABLE assessment_responses (
    assessment_id UUID NOT NULL REFERENCES assessments(id) ON DELETE CASCADE,
    question_index SMALLINT NOT NULL,
    response_value SMALLINT NOT NULL,
    PRIMARY KEY (assessment_id, question_index),
    CONSTRAINT assessment_responses_question_index_valid CHECK (question_index >= 0),
    CONSTRAINT assessment_responses_value_valid CHECK (response_value BETWEEN 0 AND 3)
);

CREATE TABLE alerts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    assessment_id UUID NOT NULL REFERENCES assessments(id) ON DELETE CASCADE,
    risk_level VARCHAR(10) NOT NULL,
    status VARCHAR(10) NOT NULL DEFAULT 'open',
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT alerts_risk_level_valid CHECK (risk_level IN ('minimal', 'mild', 'moderate', 'severe')),
    CONSTRAINT alerts_status_valid CHECK (status IN ('open', 'resolved'))
);

CREATE TABLE appointments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    counselor_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    scheduled_at TIMESTAMPTZ NOT NULL,
    reason VARCHAR(500) NOT NULL,
    meeting_type VARCHAR(20) NOT NULL DEFAULT 'virtual',
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    notes VARCHAR(1000),
    counselor_notes VARCHAR(500),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT appointments_different_participants CHECK (user_id <> counselor_id),
    CONSTRAINT appointments_meeting_type_valid CHECK (meeting_type IN ('virtual', 'in-person')),
    CONSTRAINT appointments_status_valid CHECK (status IN ('pending', 'confirmed', 'completed', 'cancelled'))
);

CREATE TABLE messages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    sender_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    recipient_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    body VARCHAR(2000) NOT NULL,
    read_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT messages_different_participants CHECK (sender_id <> recipient_id)
);

CREATE TABLE interventions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(50) NOT NULL,
    content VARCHAR(1000) NOT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE FUNCTION set_updated_at()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$;

CREATE TRIGGER users_set_updated_at
BEFORE UPDATE ON users
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER resources_set_updated_at
BEFORE UPDATE ON resources
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER mood_logs_set_updated_at
BEFORE UPDATE ON mood_logs
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER assessments_set_updated_at
BEFORE UPDATE ON assessments
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER alerts_set_updated_at
BEFORE UPDATE ON alerts
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER appointments_set_updated_at
BEFORE UPDATE ON appointments
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER messages_set_updated_at
BEFORE UPDATE ON messages
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER interventions_set_updated_at
BEFORE UPDATE ON interventions
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE INDEX users_role_created_at_idx ON users (role, created_at DESC);
CREATE INDEX users_parent_id_idx ON users (parent_id) WHERE parent_id IS NOT NULL;
CREATE INDEX password_reset_tokens_expires_at_idx ON password_reset_tokens (expires_at);
CREATE INDEX resources_category_created_at_idx ON resources (category, created_at DESC);
CREATE INDEX mood_logs_user_logged_at_idx ON mood_logs (user_id, logged_at DESC);
CREATE INDEX mood_log_tags_tag_idx ON mood_log_tags (tag);
CREATE INDEX assessments_user_taken_at_idx ON assessments (user_id, taken_at DESC);
CREATE INDEX assessments_risk_taken_at_idx ON assessments (risk_level, taken_at DESC);
CREATE INDEX alerts_status_created_at_idx ON alerts (status, created_at DESC);
CREATE INDEX alerts_user_created_at_idx ON alerts (user_id, created_at DESC);
CREATE INDEX appointments_user_scheduled_at_idx ON appointments (user_id, scheduled_at DESC);
CREATE INDEX appointments_counselor_scheduled_at_idx ON appointments (counselor_id, scheduled_at DESC);
CREATE INDEX appointments_status_scheduled_at_idx ON appointments (status, scheduled_at DESC);
CREATE INDEX messages_sender_created_at_idx ON messages (sender_id, created_at DESC);
CREATE INDEX messages_recipient_created_at_idx ON messages (recipient_id, created_at DESC);
CREATE INDEX messages_unread_recipient_idx ON messages (recipient_id, created_at DESC) WHERE read_at IS NULL;
CREATE INDEX interventions_user_type_created_at_idx ON interventions (user_id, type, created_at DESC);
