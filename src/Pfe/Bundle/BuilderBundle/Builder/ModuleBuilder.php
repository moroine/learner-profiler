<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Pfe\Bundle\CoreBundle\Entity\Theme;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Description of ThemeBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ThemeBuilder extends AbstractMoocBuilder
{

    protected static $BUILDER_NAME = "Theme";

    public function build(OutputInterface $output)
    {
        $this->buildSection($output);
    }

    public function buildSection(OutputInterface $output)
    {
        $offset = 0;
        $criteria = array('section' => null);
        $max = $this->countEntityBatches($criteria);
        if ($max === 0) {
            $output->writeln("<info>All Sections are built</info>");
            return;
        }
        $output->writeln("<info>Building Sections</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $themes Theme[] */
        while ($themes = $this->getEntityBatch($criteria, $offset)) {
            foreach ($themes as $key => $theme) {
                $section = $this->doctrine->getRepository("PfeCoreBundle:Section")->findOneBy(array("mooc_id" => $theme->getSectionMoocId()));
                if ($section) {
                    $theme->setSection($section);
                    $this->doctrine->persist($section);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $offset += static::$BATCH_SIZE;
            $this->flushEntities();
        }

        $this->outputStats($output);
        $progress->finish();
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Theme");
    }

}
