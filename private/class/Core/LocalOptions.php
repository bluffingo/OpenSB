<?php

namespace OpenSB\class\Core;

class LocalOptions
{
    private array $options;

    public function __construct() {
        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);

            // the finalium 2/biscuit frontend is now internally called "biscuit" instead of "qobo".
            // to avoid a bug where the old userlink implementation is used in templatingtwigextension,
            // automatically update SBOPTIONS on the fly.
            if ($this->options["skin"] == "qobo")
            {
                $this->options["skin"] = "biscuit";
                setcookie("SBOPTIONS", base64_encode(json_encode($this->options)), 2147483647);
            }
        } else {
            // NOTE: dont add any more default options.

            // TODO
            /*
            $defaultSkin = "biscuit"; // NOTE: biscuit is deprecated but charla isn't shipped by default
            if ($this->isChazizSquareBracketInstance() && !Utilities::isChazizTestInstance()) {
                $defaultSkin = "charla";
            }
            */

            $defaultSkin = "charla";

            $this->options = [
                "skin" => $defaultSkin,
                "theme" => "default",
                "sounds" => false,
            ];
            setcookie("SBOPTIONS", base64_encode(json_encode($this->options)), 2147483647);
        }
    }

    public function getOptions(): array {
        return $this->options;
    }
}