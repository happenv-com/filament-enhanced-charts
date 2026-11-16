<?php

return [
    'non_additive' => 'Chart preset [:preset] cannot draw the measure [:measure]: it is a ratio, average or distinct count, and its parts do not add up to a whole.',
    'semi_additive' => 'Chart preset [:preset] cannot draw the measure [:measure] across the time dimension [:dimension]: it is a stock level, and its values add up across categories but not across time.',
    'suggestion' => 'Use a preset that does not split a total, such as :presets.',
];
