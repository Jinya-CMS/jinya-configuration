import type {ReactNode} from 'react';
import clsx from 'clsx';
import Heading from '@theme/Heading';
import styles from './styles.module.css';

type FeatureItem = {
  title: string;
  description: ReactNode;
};

const FeatureList: FeatureItem[] = [
  {
    title: 'Easy to Use',
    description: (
      <>
        Jinya Configuration provides a simple and intuitive API to access your configuration values,
        no matter where they are stored.
      </>
    ),
  },
  {
    title: 'Multiple Adapters',
    description: (
      <>
        Built-in support for environment variables, INI files, and arrays.
        Chain multiple adapters to define search priority.
      </>
    ),
  },
  {
    title: 'Modern PHP',
    description: (
      <>
        Leverages modern PHP features to provide a robust and type-safe configuration management
        solution.
      </>
    ),
  },
];

function Feature({title, description}: FeatureItem) {
  return (
    <div className={clsx('col col--4')}>
      <div className="text--center padding-horiz--md">
        <Heading as="h3">{title}</Heading>
        <p>{description}</p>
      </div>
    </div>
  );
}

export default function HomepageFeatures(): ReactNode {
  return (
    <section className={styles.features}>
      <div className="container">
        <div className="row">
          {FeatureList.map((props, idx) => (
            <Feature key={idx} {...props} />
          ))}
        </div>
      </div>
    </section>
  );
}
