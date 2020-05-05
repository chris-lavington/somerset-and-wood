<?php

namespace SomersetWood\ArticleWidget\Block;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Article extends Template implements BlockInterface
{

    protected $_template = "article.phtml";

}