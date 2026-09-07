# Production Recovery Procedure (tested checklist)

This document is the tested recovery checklist to follow when a production verification step fails after a deployment. It is intended to be executed only by personnel with rollback/deploy privileges (Release Manager or Site Operations team).

## Key actions (high level)

1. Traffic mitigation
   - Pause or redirect public traffic if supported by load balancer/CDN.
   - Put the site into maintenance mode (brief notice page) to reduce write activity.

2. Triage & diagnostics
   - Collect the failing logs (web server, PHP, application logs) and the exact failing request(s).
   - Confirm the time window and commit/tag that introduced the regression.

3. Data recovery and backups
   - Confirm last known-good backup timestamp (automated backup via UpdraftPlus or platform backups).
   - If the deployment included database migrations, follow the migration rollback steps documented with the release; if not available, restore database from last-good backup into a staging environment and validate.

4. Rollback
   - Authorized personnel (Release Manager / Site Operations) perform rollback to the previous release tag or production branch state.
   - Restore database from the verified backup if data divergence is detected and documented in the release notes.

5. Validation
   - Run the production verification checklist against the rolled-back state.
   - Confirm monitoring (synthetic tests, error rate, uptime) returns to expected baselines.

6. Communication
   - Notify stakeholders (ops, dev leads, communication channel) with status updates and post-mortem assignment.

## Authority & contacts

- Rollback may be initiated only by the Release Manager or Site Operations personnel with deploy privileges.
- The team must record the exact steps taken and the time window of the rollback.

## Notes

- Always perform recovery steps in a controlled window and validate on a staging clone when possible before re-applying any fixes in production.
- For database-sensitive rollbacks, prefer writing a targeted recovery script (partial row-level recovery) instead of a full restore when possible to minimize data loss.
