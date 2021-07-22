from pathlib import Path
import re
import platform

exts = [".twig", ".php"]
paths = [p for p in Path("./").rglob('*') if p.suffix in exts]

strings = []

# add relativetime setting at the very top
strings.append("\\\\RelativeTime\\\\Languages\\\\English")
strings.append("\n")

for path in paths:
	# Ignore composer dependency files
	if platform.system() == "Windows":
		if "vendor\\" in str(path): continue
		if "templates\\cache\\" in str(path): continue
	else:
		if "vendor/" in str(path): continue
		if "templates/cache/" in str(path): continue

	with open(path, "r", encoding='Latin-1') as f:
		file_matches = re.findall('__\("([^"]+)"', f.read())
		if len(file_matches):
			if len(strings):
				print(path)
				if strings[-1] != "\n":
					strings.append("\n")

		for match in file_matches:
			# prevent duplicate strings (i.e. 6 "views")
			if match in strings:
				continue
			strings.append(match)
			print(match)

with open("lib/lang/template.json", "w") as f:
	f.write("{\n")
	i = 0
	for string in strings:
		i += 1
		if string == "\n":
			f.write("\n")
			continue
		f.write('\t"%s": "%s"' % (string, string))
		if not len(strings) - 1 == i:
			f.write(",")
		f.write('\n')
	f.write("}")
