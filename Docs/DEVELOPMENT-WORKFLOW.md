# Development Workflow

## Branch Strategy

- main — production-ready code
- develop — active development
- feature/* — feature-specific work

## Commit Standards

Use descriptive commits.

Examples:

feat: add project content architecture
fix: resolve mobile navigation issue
perf: optimize image loading
docs: update deployment documentation

## Testing

Before merging:

- PHP errors checked
- JavaScript console checked
- Responsive layouts checked
- WordPress functionality tested
- Database changes verified
- Performance checked

## Deployment

1. Tests pass
2. Code reviewed
3. Documentation updated
4. Backup created
5. Staging tested
6. Production deployment approved
7. Production verified — Acceptance tests executed in production window and monitoring validated. If verification fails, follow the documented recovery procedure: see Docs/RECOVERY-PROCEDURE.md for the tested recovery checklist (traffic mitigation, backup restore, data reconciliation) and escalation contacts. The designated Release Manager or Site Operations team (roles with deploy/rollback privileges) are authorized to initiate rollback; they must execute the recovery checklist and notify stakeholders before re-opening public traffic.
   - **Failure path**: If verification fails, initiate rollback procedure following [recovery protocol](#recovery-procedure). Rollback authority: Site Manager or DevOps Lead. Required actions: restore from backup, verify traffic routing, confirm data integrity.
