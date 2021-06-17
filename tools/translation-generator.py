from pathlib import Path
import re
from natsort import natsorted

exts = [".twig", ".php"]
paths = [p for p in Path("./").rglob('*') if p.suffix in exts]

strings = []

for path in paths:
	# Ignore composer dependency files
	if "vendor/" in str(path): continue

	with open(path, "r") as f:
		file_matches = re.findall('__\("([^"]+)"', f.read())

		for match in file_matches:
			# prevent duplicate strings (i.e. 6 "views")
			if match in strings:
				continue
			strings.append(match)

# Give the strings a reproducible order
strings = natsorted(strings, reverse=False)

with open("lib/lang/template_new.json", "w") as f:
	f.write("{\n")
	i = 0
	for string in strings:
		i += 1
		f.write('\t"%s": ""' % string)
		if not len(strings) == i:
			f.write(",")
		f.write('\n')
	f.write("}")
