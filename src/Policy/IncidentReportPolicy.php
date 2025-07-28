<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\IncidentReport;
use Authorization\IdentityInterface;

/**
 * IncidentReport policy
 */
class IncidentReportPolicy
{
    private $module = 'IncidentReports';
    /**
     * Check if $user can add IncidentReport
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\IncidentReport $incidentReport
     * @return bool
     */
    public function canAdd(IdentityInterface $user, IncidentReport $incidentReport)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit IncidentReport
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\IncidentReport $incidentReport
     * @return bool
     */
    public function canEdit(IdentityInterface $user, IncidentReport $incidentReport)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete IncidentReport
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\IncidentReport $incidentReport
     * @return bool
     */
    public function canDelete(IdentityInterface $user, IncidentReport $incidentReport)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view IncidentReport
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\IncidentReport $incidentReport
     * @return bool
     */
    public function canView(IdentityInterface $user, IncidentReport $incidentReport)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
