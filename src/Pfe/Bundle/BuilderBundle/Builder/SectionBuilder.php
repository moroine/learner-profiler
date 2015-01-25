<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Pfe\Bundle\CoreBundle\Entity\Section;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Description of SectionBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class SectionBuilder extends AbstractMoocBuilder
{

    protected static $BUILDER_NAME = "Section";

    public function build(OutputInterface $output)
    {
        $this->buildTheme($output);
    }

    public function buildTheme(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('theme' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("<info>>>>Sections<<< All Themes are built</info>");
            return;
        }
        $output->writeln("<info>>>>Sections<<< Building Themes</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $sections Section[] */
        while ($sections = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($sections as $key => $section) {
                $theme = $this->doctrine->getRepository("PfeCoreBundle:Theme")->findOneBy(array("mooc_id" => $section->getMoocThemeId()));
                if ($theme) {
                    $section->setTheme($theme);
                    $this->doctrine->merge($section);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Section");
    }

}
